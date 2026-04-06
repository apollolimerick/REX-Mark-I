<?php
session_start();
require_once 'REX_DB_Handler.php'; // Requires your DB connection

$sys_msg = '';
$sys_status = 'info'; // info, success, error

// --- HELPER FUNCTION: Random Array Value ---
function get_rand($array) {
    return $array[array_rand($array)];
}

// --- DATA DICTIONARIES FOR GENERATION ---
$first_names = ['James', 'Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Mateo', 'Sofia', 'Lukas', 'Yuki', 'Chen', 'Aisha', 'Oliver', 'Isabella', 'Ali', 'Zoe', 'Hans', 'Greta', 'Lars', 'Freya'];
$last_names = ['Smith', 'Johnson', 'Williams', 'Brown', 'Kovacs', 'Nagy', 'Toth', 'Garcia', 'Martinez', 'Wang', 'Li', 'Kim', 'Muller', 'Schmidt', 'Ali', 'Hassan', 'Patel', 'Nguyen', 'Silva', 'Kumar'];
$cities_data = [
    ['Budapest', 'Pest', 'Hungary', 'Europe'],
    ['Berlin', 'Berlin', 'Germany', 'Europe'],
    ['Austin', 'Texas', 'United States', 'North America'],
    ['Toronto', 'Ontario', 'Canada', 'North America'],
    ['Tokyo', 'Tokyo', 'Japan', 'Asia'],
    ['Seoul', 'Seoul', 'South Korea', 'Asia'],
    ['Sydney', 'New South Wales', 'Australia', 'Oceania'],
    ['Cape Town', 'Western Cape', 'South Africa', 'Africa'],
    ['Sao Paulo', 'Sao Paulo', 'Brazil', 'South America'],
    ['London', 'England', 'United Kingdom', 'Europe']
];
$street_names = ['Main St', 'Oak Ave', 'Pine Ln', 'Maple Dr', 'Cedar Ct', 'Elm St', 'Washington Blvd', 'Lakeview Trl', 'Sunset Pkwy', 'Highland Rd'];
$property_types = ["Single-Family Detached", "Condominium / Apartment", "Townhouse / Terraced", "Multi-Family (2-4 Units)", "Mixed-Use"];
$tenure_types = ["Freehold (Fee Simple)", "Leasehold"];

// =========================================================================
// ACTION: MASTER SEED (POPULATE ALL 3 TABLES INDEPENDENTLY)
// =========================================================================
if (isset($_POST['master_seed'])) {
    $num_users = isset($_POST['num_users']) ? max(0, intval($_POST['num_users'])) : 0;
    $num_public = isset($_POST['num_public']) ? max(0, intval($_POST['num_public'])) : 0;
    $num_private = isset($_POST['num_private']) ? max(0, intval($_POST['num_private'])) : 0;

    try {
        $conn->begin_transaction();
        
        $homeowner_ids = [];

        // --- STEP 1: GENERATE USERS ---
        if ($num_users > 0) {
            $stmt_user = $conn->prepare("INSERT INTO users (legal_name, email, password_hash, phone_number, tax_id_number, country_of_residence, role, kyc_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            for ($i = 0; $i < $num_users; $i++) {
                $fname = get_rand($first_names);
                $lname = get_rand($last_names);
                $name = $fname . ' ' . $lname;
                
                $hash = substr(md5(mt_rand()), 0, 6);
                $email = strtolower($fname . '.' . $lname . $hash . '@rex-sim.com');
                $pass = password_hash('password123', PASSWORD_DEFAULT);
                $phone = '+1' . mt_rand(1000000000, 9999999999);
                $tax_id = 'TAX-' . mt_rand(100000, 999999);
                $loc = get_rand($cities_data);
                $country = $loc[2];
                
                // ~40% homeowners, ~60% investors
                $role = (mt_rand(1, 100) <= 40) ? 'homeowner' : 'investor';
                $kyc = get_rand(['pending', 'approved', 'approved', 'approved', 'rejected']); 
                
                $stmt_user->bind_param("ssssssss", $name, $email, $pass, $phone, $tax_id, $country, $role, $kyc);
                $stmt_user->execute();
                
                if ($role === 'homeowner') {
                    $homeowner_ids[] = $conn->insert_id;
                }
            }
            $stmt_user->close();
        }

        // --- STEP 2: GENERATE PUBLIC ASSETS ---
        if ($num_public > 0) {
            // If no homeowners were generated in this batch, fetch existing ones from the DB
            if (empty($homeowner_ids)) {
                $res = $conn->query("SELECT id FROM users WHERE role = 'homeowner'");
                while ($row = $res->fetch_assoc()) {
                    $homeowner_ids[] = $row['id'];
                }
            }
            
            // Failsafe: if the DB is completely empty and they asked for 0 users but 300 properties
            if (empty($homeowner_ids)) {
                $conn->query("INSERT INTO users (legal_name, email, role) VALUES ('System Genesis Owner', 'genesis@rex-sim.com', 'homeowner')");
                $homeowner_ids[] = $conn->insert_id;
            }

            $stmt_pub = $conn->prepare("INSERT INTO property_public_market (
                owner_id, city, state_region, country, property_type, tenure_type,
                official_living_area, official_lot_size, year_built, total_bedrooms, wet_rooms,
                outstanding_debt, equity_target, listing_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            for ($i = 0; $i < $num_public; $i++) {
                $owner_id = get_rand($homeowner_ids);
                $loc = get_rand($cities_data);
                
                $prop_type = get_rand($property_types);
                $tenure = get_rand($tenure_types);
                
                $living_area = mt_rand(40, 400) + (mt_rand(0, 99) / 100);
                $lot_size = $living_area + mt_rand(0, 1000);
                $year = mt_rand(1900, 2024);
                $beds = mt_rand(1, 6);
                $baths = mt_rand(1, 4);
                
                $val = mt_rand(100000, 5000000);
                $debt = mt_rand(0, $val * 0.8);
                $target = mt_rand(1, 25);
                $status = get_rand(['pending_audit', 'underwritten', 'active_market', 'active_market']);
                
                $stmt_pub->bind_param("isssssddiiidds", 
                    $owner_id, $loc[0], $loc[1], $loc[2], $prop_type, $tenure,
                    $living_area, $lot_size, $year, $beds, $baths,
                    $debt, $target, $status
                );
                $stmt_pub->execute();
            }
            $stmt_pub->close();
        }

        // --- STEP 3: GENERATE PRIVATE COMPLIANCE ---
        $actual_private = 0;
        if ($num_private > 0) {
            // Only fetch public properties that DO NOT already have a private document (1-to-1 strict relationship)
            $res = $conn->query("SELECT id FROM property_public_market WHERE id NOT IN (SELECT property_id FROM property_private_compliance) LIMIT $num_private");
            
            $unmatched_props = [];
            while ($row = $res->fetch_assoc()) {
                $unmatched_props[] = $row['id'];
            }

            if (!empty($unmatched_props)) {
                $stmt_priv = $conn->prepare("INSERT INTO property_private_compliance (property_id, street_address, postal_code, consent_disclaimer, ownership_doc) VALUES (?, ?, ?, ?, ?)");
                
                foreach ($unmatched_props as $prop_id) {
                    $street = mt_rand(1, 9999) . ' ' . get_rand($street_names);
                    $zip = mt_rand(10000, 99999);
                    $consent = 1;
                    $dummy_blob = "DUMMY_PDF_BINARY_DATA_" . md5(mt_rand()); 
                    
                    $stmt_priv->bind_param("issib", $prop_id, $street, $zip, $consent, $dummy_blob);
                    $stmt_priv->send_long_data(4, $dummy_blob);
                    $stmt_priv->execute();
                    $actual_private++;
                }
                $stmt_priv->close();
            }
        }
        
        $conn->commit();
        $sys_msg = "Seeded: $num_users Users | $num_public Assets | $actual_private Docs (Capped to available properties without documents).";
        $sys_status = "success";

    } catch (Exception $e) {
        $conn->rollback();
        $sys_msg = "Error executing Master Seed: " . $e->getMessage();
        $sys_status = "error";
    }
}

// =========================================================================
// ACTION: GENERATE BLOCKCHAIN TXT
// =========================================================================
if (isset($_POST['gen_blockchain'])) {
    // Add blockchain structure here
    $sys_msg = "Blockchain transaction log generation triggered. (Txt generation skipped for now).";
    $sys_status = "info";
}

// Fetch current table counts for UI display
$count_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$count_public = $conn->query("SELECT COUNT(*) FROM property_public_market")->fetch_row()[0] ?? 0;
$count_private = $conn->query("SELECT COUNT(*) FROM property_private_compliance")->fetch_row()[0] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REX Master Control Terminal</title>
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
        .header-tag { font-size: 15px; color: var(--brand-blue); font-weight: bold; text-transform: uppercase; letter-spacing: 1px;}

        .container { max-width: 1000px; margin: 0 auto; padding: 0 40px 80px 40px; }
        h1 { font-size: 32px; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .subtitle { color: var(--text-muted); font-size: 15px; margin-bottom: 30px; font-family: monospace; }

        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 32px; backdrop-filter: blur(12px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 24px; }
        .card-title { font-size: 16px; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; font-weight: bold; border-bottom: 1px solid var(--border); padding-bottom: 10px;}
        
        .sys-msg { padding: 16px; border-radius: 10px; margin-bottom: 24px; font-size: 15px; font-weight: bold; display: flex; align-items: center; gap: 10px;}
        .sys-msg.success { background: rgba(16, 185, 129, 0.1); color: var(--buy-green); border: 1px solid rgba(16, 185, 129, 0.3); }
        .sys-msg.error { background: rgba(239, 68, 68, 0.1); color: var(--sell-red); border: 1px solid rgba(239, 68, 68, 0.3); }
        .sys-msg.info { background: rgba(59, 130, 246, 0.1); color: var(--brand-blue); border: 1px solid rgba(59, 130, 246, 0.3); }

        .control-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        
        .control-box { background: rgba(0,0,0,0.3); border: 1px solid var(--border); padding: 24px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;}
        .control-box h3 { font-size: 18px; margin-bottom: 8px;}
        .control-box p { color: var(--text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.5;}
        
        .stats-badge { background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 14px; color: var(--brand-blue); margin-bottom: 20px; display: inline-block;}

        .input-wrapper { display: flex; align-items: center; background: rgba(0,0,0,0.5); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin-bottom: 8px;}
        .input-wrapper:focus-within { border-color: var(--brand-blue); }
        .input-wrapper span { width: 140px; padding-left: 16px; color: var(--text-muted); font-family: monospace; font-size: 13px;}
        input[type="number"] { background: transparent; border: none; color: white; padding: 12px 16px; font-size: 15px; outline: none; width: 100%; font-family: monospace;}

        .btn { border: none; padding: 14px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; color: white; transition: all 0.2s; font-size: 15px; width: 100%; text-transform: uppercase; letter-spacing: 0.5px;}
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
        
        .btn-users { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); margin-top: 16px;}
        .btn-chain { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        
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
        MASTER DATA CONTROL
    </div>
</header>

<div class="container">
    <div>
        <h1>Database Population Terminal</h1>
        <div class="subtitle">Execute high-volume seed scripts to populate relational tables.</div>
    </div>

    <?php if ($sys_msg): ?>
        <div class="sys-msg <?= $sys_status ?>">
            <?= $sys_status === 'error' ? '⚠' : ($sys_status === 'success' ? '✓' : 'ℹ') ?> 
            <?= $sys_msg ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="card">
            <div class="card-title">System Architecture Status</div>
            
            <div style="display: flex; gap: 16px; margin-bottom: 30px;">
                <div class="stats-badge">Users: <?= number_format($count_users) ?> Rows</div>
                <div class="stats-badge">Public Assets: <?= number_format($count_public) ?> Rows</div>
                <div class="stats-badge">Private Docs: <?= number_format($count_private) ?> Rows</div>
            </div>

            <div class="control-grid">
                <div class="control-box">
                    <div>
                        <h3>Master Relational Seed</h3>
                        <p>Generates records independently while maintaining exact relational integrity across all tables.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div class="input-wrapper">
                                <span>Inject Users:</span>
                                <input type="number" name="num_users" value="300" min="0" max="5000">
                            </div>
                            <div class="input-wrapper">
                                <span>Inject Assets:</span>
                                <input type="number" name="num_public" value="300" min="0" max="5000">
                            </div>
                            <div class="input-wrapper">
                                <span>Inject Docs:</span>
                                <input type="number" name="num_private" value="300" min="0" max="5000">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="master_seed" class="btn btn-users">Execute Master Seed</button>
                </div>

                <div class="control-box">
                    <div>
                        <h3>Blockchain Ledger Synthesis</h3>
                        <p>Compiles current relational database properties and user roles into an immutable text log format simulating on-chain hash events. (Currently skipped, marked for future integration).</p>
                    </div>
                    <button type="submit" name="gen_blockchain" class="btn btn-chain">Generate Immutable Txt</button>
                </div>
            </div>

        </div>
    </form>
</div>

</body>
</html>