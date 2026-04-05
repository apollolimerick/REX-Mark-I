<?php
session_start();

// --- 1. AMM INITIALIZATION & STATE MANAGEMENT ---
if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['init_pool'])) {
    $x = floatval($_POST['init_shares']);
    $y = floatval($_POST['init_cash']);
    
    if ($x > 0 && $y > 0) {
        $_SESSION['pool_initialized'] = true;
        $_SESSION['initial_shares'] = $x;
        $_SESSION['initial_cash'] = $y;
        $_SESSION['amm_x'] = $x;
        $_SESSION['amm_y'] = $y;
        $_SESSION['k'] = $x * $y;
        $_SESSION['history'] = [];
        $_SESSION['last_math'] = null;
    }
}

$error_msg = '';

// --- 2. HANDLE ORDERS (THE DUAL MATH ENGINE) ---
if (isset($_SESSION['pool_initialized']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $input_type = $_POST['input_type']; 
    $input_amount = floatval($_POST['amount']); 
    $k = $_SESSION['k'];

    if ($input_amount <= 0) {
        $error_msg = "Amount must be greater than 0.";
    } else {
        $old_x = $_SESSION['amm_x'];
        $old_y = $_SESSION['amm_y'];
        
        // Snapshot the initial spot price (P0) before trade execution
        $p0 = $old_y / $old_x; 

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

        // If no errors, calculate slippage, update state, and log history
        if (empty($error_msg) && isset($new_x) && isset($new_y)) {
            $_SESSION['amm_x'] = $new_x;
            $_SESSION['amm_y'] = $new_y;
            
            $cash_logged = ($input_type === 'usd') ? $amount_usd : $cash_exchanged;
            
            // The Universal Slippage Formula: | (P_avg / P0) - 1 |
            $slippage_ratio = abs(($execution_price / $p0) - 1);
            $slippage_pct = $slippage_ratio * 100;
            $price_delta_usd = abs($execution_price - $p0); 
            
            // Save Math for UI Breakdown (Added old_x and old_y)
            $_SESSION['last_math'] = [
                'old_x' => $old_x,
                'old_y' => $old_y,
                'p0' => $p0,
                'dy' => $cash_logged,
                'dx' => $shares_exchanged,
                'p_avg' => $execution_price,
                'sl_ratio' => $slippage_ratio,
                'sl_pct' => $slippage_pct
            ];

            array_unshift($_SESSION['history'], [
                'time' => date('H:i:s'),
                'type' => strtoupper($action),
                'input_mode' => $input_type,
                'shares' => $shares_exchanged,
                'cash' => $cash_logged,
                'execution_price' => $execution_price,
                'price_delta_usd' => $price_delta_usd,
                'slippage_pct' => $slippage_pct,
                'new_pool_price' => $new_y / $new_x
            ]);
        }
    }
}

// Current Live Data (Only calculate if initialized)
if (isset($_SESSION['pool_initialized'])) {
    $current_x = $_SESSION['amm_x'];
    $current_y = $_SESSION['amm_y'];
    $k = $_SESSION['k'];
    $current_price = $current_y / $current_x;

    // Calculate Graphic Percentages
    $max_visual_scale_shares = max($_SESSION['initial_shares'] * 2, $current_x);
    $max_visual_scale_cash = max($_SESSION['initial_cash'] * 2, $current_y);

    $pct_shares = min(100, ($current_x / $max_visual_scale_shares) * 100);
    $pct_cash = min(100, ($current_y / $max_visual_scale_cash) * 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REX AMM Physics Simulator</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-body: rgba(19, 17, 28, 0.85); 
            --bg-card: rgba(30, 27, 41, 0.75); 
            
            --text-main: #FFFFFF;
            --text-muted: #8A85A3;
            --border: rgba(138, 133, 163, 0.2);
            
            --buy-green: #10B981;
            --sell-red: #EF4444;
            --brand-blue: #3B82F6;
            --slip-warn: #F59E0B;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; overflow-x: hidden; }
        
        .bg-canvas { position: fixed; top: -20vh; left: -20vw; width: 140vw; height: 140vh; z-index: -2; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #050505; }
        .netflix-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; transform: rotate(-18deg) scale(1.6); width: 160vw; filter: blur(4px); opacity: 0.25; }
        .netflix-img { width: 100%; aspect-ratio: 16 / 9; background-size: cover; background-position: center; border-radius: 4px; background-color: #334155; box-shadow: 0 4px 15px rgba(0,0,0,0.8); }
        .light-halo { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 120vw; height: 120vh; background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(19, 17, 28, 0) 70%); z-index: -1; pointer-events: none; }

        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 48px; background: rgba(19, 17, 28, 0.6); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100; margin-bottom: 40px; }
        .rex-logo { display: flex; align-items: center; text-decoration: none; gap: 14px; }
        .rex-logo img { height: 36px; border-radius: 8px; }
        .rex-logo span { color: var(--text-main); font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header-tag { font-size: 15px; color: var(--text-muted); font-weight: 500; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 40px 80px 40px; }
        h1 { font-size: 32px; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .subtitle { color: var(--text-muted); font-size: 15px; margin-bottom: 30px; font-family: monospace; }

        .top-panels { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .full-panel { grid-column: 1 / -1; margin-bottom: 24px; }
        
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 32px; backdrop-filter: blur(12px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .card-title { font-size: 14px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; font-weight: bold; }
        
        .live-price { font-size: 48px; font-weight: 800; font-family: monospace; margin-bottom: 5px; letter-spacing: -1px; }
        
        .buffer-row { margin-bottom: 20px; }
        .buffer-labels { display: flex; justify-content: space-between; font-size: 13px; font-family: monospace; margin-bottom: 8px; color: var(--text-muted); }
        .bar-bg { background: rgba(0,0,0,0.4); height: 14px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
        .bar-fill { height: 100%; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        
        .form-row { display: flex; gap: 12px; margin-bottom: 16px; align-items: center; }
        .input-wrapper { display: flex; align-items: center; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; flex: 1; overflow: hidden; }
        .input-wrapper:focus-within { border-color: var(--brand-blue); background: rgba(0,0,0,0.5); }
        .type-selector { background: rgba(255,255,255,0.05); color: white; border: none; padding: 16px; font-size: 15px; font-weight: bold; cursor: pointer; outline: none; border-right: 1px solid var(--border); }
        .type-selector option { background: #13111C; }
        input[type="number"] { background: transparent; border: none; color: white; padding: 16px; font-size: 18px; outline: none; width: 100%; font-weight: 600; }
        
        .btn { border: none; padding: 16px 24px; border-radius: 10px; font-weight: bold; cursor: pointer; color: white; transition: all 0.2s; font-size: 15px; width: 100%; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
        .btn-blue { background: var(--brand-blue); }
        .btn-buy { background: var(--buy-green); }
        .btn-sell { background: var(--sell-red); }
        .btn-reset { background: rgba(239, 68, 68, 0.1); border: 1px solid var(--sell-red); color: var(--sell-red); margin-top: 15px; }
        .btn-reset:hover { background: var(--sell-red); color: white; }

        .error { background: rgba(239, 68, 68, 0.1); color: var(--sell-red); padding: 16px; border-radius: 10px; margin-bottom: 24px; font-size: 15px; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 500;}

        .math-box { background: rgba(0,0,0,0.4); padding: 20px; border-radius: 10px; border: 1px solid var(--border); font-family: monospace; font-size: 14px; line-height: 1.8; color: #a5b4fc;}
        .highlight { color: #fff; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 16px; font-size: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border); text-transform: uppercase; letter-spacing: 1px;}
        td { padding: 16px; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: monospace; }
        tr:hover td { background: rgba(255,255,255,0.03); }
        
        .badge { padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; letter-spacing: 0.5px;}
        .badge.buy { background: rgba(16, 185, 129, 0.15); color: var(--buy-green); border: 1px solid rgba(16, 185, 129, 0.3);}
        .badge.sell { background: rgba(239, 68, 68, 0.15); color: var(--sell-red); border: 1px solid rgba(239, 68, 68, 0.3);}
        .tag-small { color: var(--text-muted); font-size: 11px; margin-left: 8px; text-transform: uppercase; }

        .impact-positive { color: var(--sell-red); } 
        .impact-negative { color: var(--sell-red); } 
    </style>
</head>
<body>

<div class="light-halo"></div>
<div class="bg-canvas">
    <div class="netflix-grid">
        <?php 
            $houses = [];
            for ($i = 1; $i <= 11; $i++) { $houses[] = "Background_images/house$i.jpg"; }
            $grid_images = array_merge($houses, $houses, $houses, $houses, $houses, $houses);
            foreach ($grid_images as $img): 
        ?>
        <div class="netflix-img" style="background-image: url('<?php echo $img; ?>');"></div>
        <?php endforeach; ?>
    </div>
</div>

<header>
    <a href="?" class="rex-logo">
        <img src="Logo/REX_logo.jpg" alt="REX Logo" onerror="this.style.display='none'">
        <span>REX</span>
    </a>
    <div class="header-tag">
        AMM Physics Simulator
    </div>
</header>

<div class="container">
    <?php if (!isset($_SESSION['pool_initialized'])): ?>
        <div class="card" style="max-width: 600px; margin: 40px auto;">
            <h1 style="text-align: center; margin-bottom: 20px;">Initialize Genesis Pool</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Set the starting reserves to mathematically lock the constant pool equation (k = x &times; y).</p>
            
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom: 8px; font-weight: bold; color: var(--text-muted);">Initial Property Shares (x)</label>
                    <div class="input-wrapper">
                        <input type="number" name="init_shares" step="0.0001" placeholder="e.g. 1000" required autocomplete="off">
                    </div>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <label style="display:block; margin-bottom: 8px; font-weight: bold; color: var(--text-muted);">Initial Cash Reserves (y)</label>
                    <div class="input-wrapper">
                        <input type="number" name="init_cash" step="0.01" placeholder="e.g. 10000000" required autocomplete="off">
                    </div>
                </div>
                
                <button type="submit" name="init_pool" class="btn btn-blue">Boot AMM Engine</button>
            </form>
        </div>
    <?php else: ?>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <h1>Mathematical Core (x &times; y = k)</h1>
                <div class="subtitle">Constant (k): <?= number_format($k, 2) ?> | Genesis Price: $<?= number_format($_SESSION['initial_cash'] / $_SESSION['initial_shares'], 2) ?></div>
            </div>
            <form method="POST">
                <button type="submit" name="reset" value="1" class="btn btn-reset" style="margin-top: 0; padding: 12px 24px; width: auto;">Destroy Pool</button>
            </form>
        </div>

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
                            <span>Shares (x)</span>
                            <span><?= number_format($current_x, 4) ?></span>
                        </div>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width: <?= $pct_shares ?>%; background: var(--brand-blue);"></div>
                        </div>
                    </div>

                    <div class="buffer-row">
                        <div class="buffer-labels">
                            <span>Cash (y)</span>
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
            </div>
        </div>

        <div class="top-panels">
            <div class="card">
                <div class="card-title">Mathematical Engine Breakdown</div>
                <?php if ($_SESSION['last_math']): $m = $_SESSION['last_math']; ?>
                    <div class="math-box">
                        <span style="color: var(--text-muted);">// 1. Snapshot Spot Price (Pre-Trade)</span><br>
                        P = y / x<br>
                        P<sub>0</sub> = <?= number_format($m['old_y'], 2) ?> / <?= number_format($m['old_x'], 4) ?><br>
                        P<sub>0</sub> = <span class="highlight">$<?= number_format($m['p0'], 2) ?></span><br><br>

                        <span style="color: var(--text-muted);">// 2. Average Execution Price</span><br>
                        P<sub>avg</sub> = &Delta;y / &Delta;x<br>
                        P<sub>avg</sub> = <?= number_format($m['dy'], 2) ?> / <?= number_format($m['dx'], 4) ?><br>
                        P<sub>avg</sub> = <span class="highlight">$<?= number_format($m['p_avg'], 2) ?></span><br><br>

                        <span style="color: var(--text-muted);">// 3. Price Impact Penalty</span><br>
                        Sl = |(P<sub>avg</sub> / P<sub>0</sub>) - 1|<br>
                        Sl = |(<?= number_format($m['p_avg'], 2) ?> / <?= number_format($m['p0'], 2) ?>) - 1|<br>
                        Sl = <?= number_format($m['sl_ratio'], 4) ?> <span class="highlight">(<?= number_format($m['sl_pct'], 2) ?>%)</span>
                    </div>
                <?php else: ?>
                    <div class="math-box" style="text-align:center; color: var(--text-muted); padding: 50px 20px;">
                        Execute a trade to see the algebraic breakdown of your formulas (k = x &times; y), execution price, and slippage.
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="card-title">Live Bonding Curve (k = x &times; y)</div>
                <div style="height: 280px; width: 100%;">
                    <canvas id="bondingCurveChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card full-panel">
            <div class="card-title">Transaction Ledger</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                            <th>Cash (&Delta;y)</th>
                            <th>Shares (&Delta;x)</th>
                            <th>Exec Price (P<sub>avg</sub>)</th>
                            <th>Price Impact ($ / %)</th>
                            <th>New Pool Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['history'])): ?>
                            <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding: 40px; font-family: -apple-system, sans-serif;">No trades executed yet. The mathematical scale is perfectly balanced.</td></tr>
                        <?php else: ?>
                            <?php foreach ($_SESSION['history'] as $tx): ?>
                                <tr>
                                    <td style="color: var(--text-muted);"><?= $tx['time'] ?></td>
                                    <td>
                                        <span class="badge <?= strtolower($tx['type']) ?>"><?= $tx['type'] ?></span>
                                    </td>
                                    <td><strong style="color: white;">$<?= number_format($tx['cash'], 2) ?></strong></td>
                                    <td><?= number_format($tx['shares'], 4) ?></td>
                                    <td>
                                        $<?= number_format($tx['execution_price'], 2) ?>
                                    </td>
                                    <td class="impact-negative">
                                        <?= $tx['type'] === 'BUY' ? '+' : '-' ?>$<?= number_format($tx['price_delta_usd'], 2) ?>
                                        <span style="color: var(--slip-warn);"> (<?= number_format($tx['slippage_pct'], 2) ?>%)</span>
                                    </td>
                                    <td style="font-weight: bold; color: white;">$<?= number_format($tx['new_pool_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            // Pull live AMM math straight from PHP
            const k = <?= $k ?>;
            const current_x = <?= $current_x ?>;
            const current_y = <?= $current_y ?>;
            const initial_shares = <?= $_SESSION['initial_shares'] ?>;
            const initial_cash = <?= $_SESSION['initial_cash'] ?>;
            
            // Dynamic Bounds: Prevent the chart from snapping if x gets massive or tiny
            const min_bound = Math.min(initial_shares, current_x);
            const max_bound = Math.max(initial_shares, current_x);
            
            const min_x = min_bound * 0.2; 
            const max_x = max_bound * 2.5; 
            const step = (max_x - min_x) / 60; 

            const curveData = [];
            for(let x = min_x; x <= max_x; x += step) {
                curveData.push({ x: x, y: k / x });
            }

            const ctx = document.getElementById('bondingCurveChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        {
                            label: 'Pool State (Current)',
                            data: [{ x: current_x, y: current_y }],
                            backgroundColor: '#10B981', 
                            borderColor: '#FFFFFF',
                            borderWidth: 2,
                            pointRadius: 8,
                            pointHoverRadius: 10,
                            type: 'scatter',
                            order: 1
                        },
                        {
                            label: 'y = k/x',
                            data: curveData,
                            borderColor: 'rgba(59, 130, 246, 0.8)', 
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            pointRadius: 0,
                            tension: 0.4,
                            order: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { labels: { color: '#8A85A3' } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let x = context.parsed.x.toFixed(4);
                                    let y = context.parsed.y.toFixed(2);
                                    let price = (context.parsed.y / context.parsed.x).toFixed(2);
                                    return `Shares: ${x} | Cash: $${y} | Price: $${price}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { 
                            type: 'linear', 
                            title: { display: true, text: 'Shares (x)', color: '#8A85A3' }, 
                            grid: { color: 'rgba(138, 133, 163, 0.1)' }, 
                            ticks: { color: '#8A85A3' } 
                        },
                        y: { 
                            type: 'linear', 
                            title: { display: true, text: 'Cash ($y)', color: '#8A85A3' }, 
                            grid: { color: 'rgba(138, 133, 163, 0.1)' }, 
                            ticks: { color: '#8A85A3' },
                            // Cap the Y axis to prevent extreme steepness from squashing the visual curve
                            suggestedMax: Math.max(initial_cash, current_y) * 2
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>

</body>
</html>