<?php
session_start();

// --- 1. AMM INITIALIZATION & STATE MANAGEMENT ---
$initial_shares = 1000;
$initial_cash = 10000000; // $10,000,000
$k = $initial_shares * $initial_cash; // 10,000,000,000

// Reset or Initialize Session
if (!isset($_SESSION['amm_x']) || isset($_POST['reset'])) {
    $_SESSION['amm_x'] = $initial_shares;
    $_SESSION['amm_y'] = $initial_cash;
    $_SESSION['history'] = [];
}

$error_msg = '';

// --- 2. HANDLE ORDERS (THE DUAL MATH ENGINE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $input_type = $_POST['input_type']; // 'usd' or 'shares'
    $input_amount = floatval($_POST['amount']); 

    if ($input_amount <= 0) {
        $error_msg = "Amount must be greater than 0.";
    } else {
        $old_x = $_SESSION['amm_x'];
        $old_y = $_SESSION['amm_y'];

        // SCENARIO A: User entered a DOLLAR amount
        if ($input_type === 'usd') {
            $amount_usd = $input_amount;
            
            if ($action === 'buy') {
                $new_y = $old_y + $amount_usd;
                $new_x = $k / $new_y;
                $shares_exchanged = $old_x - $new_x;
                $execution_price = $amount_usd / $shares_exchanged;
                
            } elseif ($action === 'sell') {
                if ($amount_usd >= $old_y) {
                    $error_msg = "Bank run prevented! AMM does not have enough cash liquidity for this extraction.";
                } else {
                    $new_y = $old_y - $amount_usd;
                    $new_x = $k / $new_y;
                    $shares_exchanged = $new_x - $old_x;
                    $execution_price = $amount_usd / $shares_exchanged;
                }
            }
        } 
        
        // SCENARIO B: User entered a SHARE amount
        elseif ($input_type === 'shares') {
            $amount_shares = $input_amount;
            
            if ($action === 'buy') {
                if ($amount_shares >= $old_x) {
                    $error_msg = "Inventory depleted! AMM does not have enough shares.";
                } else {
                    $new_x = $old_x - $amount_shares;
                    $new_y = $k / $new_x;
                    $cash_exchanged = $new_y - $old_y;
                    $shares_exchanged = $amount_shares;
                    $execution_price = $cash_exchanged / $shares_exchanged;
                }
                
            } elseif ($action === 'sell') {
                $new_x = $old_x + $amount_shares;
                $new_y = $k / $new_x;
                $cash_exchanged = $old_y - $new_y;
                $shares_exchanged = $amount_shares;
                $execution_price = $cash_exchanged / $shares_exchanged;
            }
        }

        // If no errors, update state and log history
        if (empty($error_msg) && isset($new_x) && isset($new_y)) {
            $_SESSION['amm_x'] = $new_x;
            $_SESSION['amm_y'] = $new_y;
            
            $cash_logged = ($input_type === 'usd') ? $amount_usd : $cash_exchanged;
            
            array_unshift($_SESSION['history'], [
                'time' => date('H:i:s'),
                'type' => strtoupper($action),
                'input_mode' => $input_type,
                'shares' => $shares_exchanged,
                'cash' => $cash_logged,
                'execution_price' => $execution_price,
                'new_pool_price' => $new_y / $new_x
            ]);
        }
    }
}

// Current Live Data
$current_x = $_SESSION['amm_x'];
$current_y = $_SESSION['amm_y'];
$current_price = $current_y / $current_x;

// Calculate Graphic Percentages (Max scale visually capped)
$max_visual_scale_shares = max($initial_shares * 2, $current_x);
$max_visual_scale_cash = max($initial_cash * 2, $current_y);

$pct_shares = min(100, ($current_x / $max_visual_scale_shares) * 100);
$pct_cash = min(100, ($current_y / $max_visual_scale_cash) * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REX AMM Physics Simulator</title>
    <style>
        :root {
            /* Glassmorphism Theme Variables */
            --bg-body: rgba(19, 17, 28, 0.85); 
            --bg-card: rgba(30, 27, 41, 0.75); 
            --bg-card-hover: rgba(40, 37, 51, 0.85); 
            
            --text-main: #FFFFFF;
            --text-muted: #8A85A3;
            --border: rgba(138, 133, 163, 0.2);
            
            --buy-green: #10B981;
            --sell-red: #EF4444;
            --brand-blue: #3B82F6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; overflow-x: hidden; }
        
        /* BACKGROUND CANVAS SYSTEM */
        .bg-canvas {
            position: fixed;
            top: -20vh; left: -20vw; width: 140vw; height: 140vh;
            z-index: -2;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #050505;
        }
        .netflix-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            transform: rotate(-18deg) scale(1.6);
            width: 160vw;
            filter: blur(4px);
            opacity: 0.25;
            transition: opacity 0.5s, filter 0.5s;
        }
        .netflix-img {
            width: 100%;
            aspect-ratio: 16 / 9;
            background-size: cover;
            background-position: center;
            border-radius: 4px;
            background-color: #334155; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.8);
        }
        .light-halo {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120vw; height: 120vh;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(19, 17, 28, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }

        /* HEADER */
        header { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 20px 48px; background: rgba(19, 17, 28, 0.6); 
            backdrop-filter: blur(12px); border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100; margin-bottom: 40px;
        }
        .rex-logo { display: flex; align-items: center; text-decoration: none; gap: 14px; }
        .rex-logo img { height: 36px; border-radius: 8px; }
        .rex-logo span { color: var(--text-main); font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header-tag { font-size: 15px; color: var(--text-muted); font-weight: 500; }

        /* MAIN CONTAINER */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 40px 80px 40px; }
        h1 { font-size: 32px; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .subtitle { color: var(--text-muted); font-size: 15px; margin-bottom: 30px; font-family: monospace; }

        .top-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px; }
        
        /* GLASS CARDS */
        .card { 
            background: var(--bg-card); border: 1px solid var(--border); 
            border-radius: 16px; padding: 32px; backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .card-title { font-size: 14px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; font-weight: bold; }
        
        .live-price { font-size: 48px; font-weight: 800; font-family: monospace; margin-bottom: 5px; letter-spacing: -1px; }
        
        /* BUFFERS */
        .buffer-row { margin-bottom: 20px; }
        .buffer-labels { display: flex; justify-content: space-between; font-size: 13px; font-family: monospace; margin-bottom: 8px; color: var(--text-muted); }
        .bar-bg { background: rgba(0,0,0,0.4); height: 14px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
        .bar-fill { height: 100%; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* FORMS */
        .form-row { display: flex; gap: 12px; margin-bottom: 16px; align-items: center; }
        
        .input-wrapper { display: flex; align-items: center; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; flex: 1; overflow: hidden; transition: border-color 0.2s;}
        .input-wrapper:focus-within { border-color: var(--brand-blue); background: rgba(0,0,0,0.5); }
        .type-selector { background: rgba(255,255,255,0.05); color: white; border: none; padding: 16px; font-size: 15px; font-weight: bold; cursor: pointer; outline: none; border-right: 1px solid var(--border); }
        .type-selector option { background: #13111C; }
        input[type="number"] { background: transparent; border: none; color: white; padding: 16px; font-size: 18px; outline: none; width: 100%; font-weight: 600; }
        
        .btn { border: none; padding: 16px 24px; border-radius: 10px; font-weight: bold; cursor: pointer; color: white; transition: all 0.2s; font-size: 15px; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
        .btn-buy { background: var(--buy-green); }
        .btn-buy:hover { background: #0ea5e9; } /* Slight color shift on hover */
        .btn-sell { background: var(--sell-red); }
        .btn-sell:hover { background: #f43f5e; }
        .btn-reset { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: var(--text-muted); width: 100%; margin-top: 15px; }
        .btn-reset:hover { background: rgba(255,255,255,0.1); color: white; }

        .error { background: rgba(239, 68, 68, 0.1); color: var(--sell-red); padding: 16px; border-radius: 10px; margin-bottom: 24px; font-size: 15px; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 500;}

        /* TABLE */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 16px; font-size: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border); text-transform: uppercase; letter-spacing: 1px;}
        td { padding: 16px; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: monospace; }
        tr:hover td { background: rgba(255,255,255,0.03); }
        
        .badge { padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; letter-spacing: 0.5px;}
        .badge.buy { background: rgba(16, 185, 129, 0.15); color: var(--buy-green); border: 1px solid rgba(16, 185, 129, 0.3);}
        .badge.sell { background: rgba(239, 68, 68, 0.15); color: var(--sell-red); border: 1px solid rgba(239, 68, 68, 0.3);}
        .tag-small { color: var(--text-muted); font-size: 11px; margin-left: 8px; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="light-halo"></div>
<div class="bg-canvas">
    <div class="netflix-grid">
        <?php 
            // Reuse the 11 base house images to create the cinematic grid background
            $houses = [];
            for ($i = 1; $i <= 11; $i++) { $houses[] = "house$i.jpg"; }
            $grid_images = array_merge($houses, $houses, $houses, $houses, $houses, $houses);
            foreach ($grid_images as $img): 
        ?>
        <div class="netflix-img" style="background-image: url('<?php echo $img; ?>');"></div>
        <?php endforeach; ?>
    </div>
</div>

<header>
    <a href="?" class="rex-logo">
        <img src="REX_logo.jpg" alt="REX Logo" onerror="this.style.display='none'">
        <span>REX</span>
    </a>
    <div class="header-tag">
        AMM Physics Simulator
    </div>
</header>

<div class="container">
    <h1>Mathematical Core ($x \times y = k$)</h1>
    <div class="subtitle">Constant ($k$): <?= number_format($k) ?></div>

    <?php if ($error_msg): ?>
        <div class="error">⚠️ <?= $error_msg ?></div>
    <?php endif; ?>

    <div class="top-panels">
        <div class="card">
            <div class="card-title">Live AMM Inventory</div>
            <div class="live-price">$<?= number_format($current_price, 2) ?> <span style="font-size: 18px; color: var(--text-muted); font-family: -apple-system, sans-serif;">/ share</span></div>
            
            <div style="margin-top: 35px;">
                <div class="buffer-row">
                    <div class="buffer-labels">
                        <span>Shares ($x)</span>
                        <span><?= number_format($current_x, 2) ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?= $pct_shares ?>%; background: var(--brand-blue);"></div>
                    </div>
                </div>

                <div class="buffer-row">
                    <div class="buffer-labels">
                        <span>Cash ($y)</span>
                        <span>$<?= number_format($current_y, 2) ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?= $pct_cash ?>%; background: var(--buy-green);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Execute Trade</div>
            <form method="POST">
                <div class="form-row">
                    <div class="input-wrapper">
                        <select name="input_type" class="type-selector">
                            <option value="usd">$ USD</option>
                            <option value="shares">Shares</option>
                        </select>
                        <input type="number" name="amount" step="0.0001" placeholder="Enter Amount..." required autocomplete="off">
                    </div>
                </div>
                <div class="form-row">
                    <button type="submit" name="action" value="buy" class="btn btn-buy" style="flex:1;">Buy from Pool</button>
                    <button type="submit" name="action" value="sell" class="btn btn-sell" style="flex:1;">Sell to Pool</button>
                </div>
            </form>

            <form method="POST" style="margin-top: 20px;">
                <button type="submit" name="reset" value="1" class="btn btn-reset">↺ Reset Pool to Genesis State</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Transaction Ledger</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Action</th>
                        <th>Cash Exchanged</th>
                        <th>Shares Exchanged</th>
                        <th>Slippage (Execution Price)</th>
                        <th>New Pool Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($_SESSION['history'])): ?>
                        <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 40px; font-family: -apple-system, sans-serif;">No trades executed yet. The mathematical scale is perfectly balanced.</td></tr>
                    <?php else: ?>
                        <?php foreach ($_SESSION['history'] as $tx): ?>
                            <tr>
                                <td style="color: var(--text-muted);"><?= $tx['time'] ?></td>
                                <td>
                                    <span class="badge <?= strtolower($tx['type']) ?>"><?= $tx['type'] ?></span>
                                    <span class="tag-small">(By <?= $tx['input_mode'] ?>)</span>
                                </td>
                                <td><strong style="color: white;">$<?= number_format($tx['cash'], 2) ?></strong></td>
                                <td><?= number_format($tx['shares'], 4) ?></td>
                                <td style="color: <?= $tx['type'] === 'BUY' ? 'var(--sell-red)' : 'var(--buy-green)' ?>;">
                                    $<?= number_format($tx['execution_price'], 2) ?>
                                </td>
                                <td style="font-weight: bold; color: white;">$<?= number_format($tx['new_pool_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>