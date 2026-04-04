<?php
// ==========================================
// 1. GENERATIVE MARKET DATABASE ENGINE
// ==========================================

$flags = [
    'United States' => '🇺🇸', 'Canada' => '🇨🇦', 'United Kingdom' => '🇬🇧',
    'Germany' => '🇩🇪', 'France' => '🇫🇷', 'Italy' => '🇮🇹',
    'Spain' => '🇪🇸', 'Netherlands' => '🇳🇱', 'Switzerland' => '🇨🇭',
    'Sweden' => '🇸🇪', 'Japan' => '🇯🇵', 'South Korea' => '🇰🇷',
    'Australia' => '🇦🇺', 'New Zealand' => '🇳🇿', 'Singapore' => '🇸🇬',
    'UAE' => '🇦🇪', 'Brazil' => '🇧🇷', 'Mexico' => '🇲🇽',
    'Argentina' => '🇦🇷', 'Chile' => '🇨🇱', 'South Africa' => '🇿🇦',
    'Hungary' => '🇭🇺', 'Austria' => '🇦🇹', 'Poland' => '🇵🇱',
    'Czechia' => '🇨🇿', 'Ireland' => '🇮🇪', 'Portugal' => '🇵🇹',
    'Greece' => '🇬🇷', 'Norway' => '🇳🇴', 'Denmark' => '🇩🇰'
];

$iso_map = [
    'United States' => 'USA', 'Canada' => 'CAN', 'United Kingdom' => 'GBR',
    'Germany' => 'DEU', 'France' => 'FRA', 'Italy' => 'ITA',
    'Spain' => 'ESP', 'Netherlands' => 'NLD', 'Switzerland' => 'CHE',
    'Sweden' => 'SWE', 'Japan' => 'JPN', 'South Korea' => 'KOR',
    'Australia' => 'AUS', 'New Zealand' => 'NZL', 'Singapore' => 'SGP',
    'UAE' => 'ARE', 'Brazil' => 'BRA', 'Mexico' => 'MEX',
    'Argentina' => 'ARG', 'Chile' => 'CHL', 'South Africa' => 'ZAF',
    'Hungary' => 'HUN', 'Austria' => 'AUT', 'Poland' => 'POL',
    'Czechia' => 'CZE', 'Ireland' => 'IRL', 'Portugal' => 'PRT',
    'Greece' => 'GRC', 'Norway' => 'NOR', 'Denmark' => 'DNK'
];

$geography = [
    'United States' => ['New York', 'Los Angeles', 'Chicago', 'Miami', 'San Francisco', 'Regional Towns'],
    'Canada' => ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa', 'Regional Towns'],
    'United Kingdom' => ['London', 'Manchester', 'Birmingham', 'Edinburgh', 'Bristol', 'Regional Towns'],
    'Germany' => ['Berlin', 'Munich', 'Frankfurt', 'Hamburg', 'Cologne', 'Regional Towns'],
    'France' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Regional Towns'],
    'Italy' => ['Rome', 'Milan', 'Naples', 'Turin', 'Florence', 'Regional Towns'],
    'Spain' => ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Regional Towns'],
    'Netherlands' => ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven', 'Regional Towns'],
    'Switzerland' => ['Zurich', 'Geneva', 'Basel', 'Lausanne', 'Bern', 'Regional Towns'],
    'Sweden' => ['Stockholm', 'Gothenburg', 'Malmo', 'Uppsala', 'Vasteras', 'Regional Towns'],
    'Japan' => ['Tokyo', 'Osaka', 'Kyoto', 'Yokohama', 'Sapporo', 'Regional Towns'],
    'South Korea' => ['Seoul', 'Busan', 'Incheon', 'Daegu', 'Daejeon', 'Regional Towns'],
    'Australia' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Regional Towns'],
    'New Zealand' => ['Auckland', 'Wellington', 'Christchurch', 'Hamilton', 'Tauranga', 'Regional Towns'],
    'Singapore' => ['Central Region', 'East Region', 'North Region', 'North-East', 'West Region'],
    'UAE' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Fujairah', 'Regional Towns'],
    'Brazil' => ['Sao Paulo', 'Rio de Janeiro', 'Brasilia', 'Salvador', 'Fortaleza', 'Regional Towns'],
    'Mexico' => ['Mexico City', 'Guadalajara', 'Monterrey', 'Puebla', 'Tijuana', 'Regional Towns'],
    'Argentina' => ['Buenos Aires', 'Cordoba', 'Rosario', 'Mendoza', 'Tucuman', 'Regional Towns'],
    'Chile' => ['Santiago', 'Valparaiso', 'Concepcion', 'La Serena', 'Antofagasta', 'Regional Towns'],
    'South Africa' => ['Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth', 'Regional Towns'],
    'Hungary' => ['Budapest', 'Debrecen', 'Szeged', 'Miskolc', 'Pecs', 'Regional Towns'],
    'Austria' => ['Vienna', 'Graz', 'Linz', 'Salzburg', 'Innsbruck', 'Regional Towns'],
    'Poland' => ['Warsaw', 'Krakow', 'Lodz', 'Wroclaw', 'Poznan', 'Regional Towns'],
    'Czechia' => ['Prague', 'Brno', 'Ostrava', 'Plzen', 'Liberec', 'Regional Towns'],
    'Ireland' => ['Dublin', 'Cork', 'Limerick', 'Galway', 'Waterford', 'Regional Towns'],
    'Portugal' => ['Lisbon', 'Porto', 'Braga', 'Aveiro', 'Faro', 'Regional Towns'],
    'Greece' => ['Athens', 'Thessaloniki', 'Patras', 'Heraklion', 'Larissa', 'Regional Towns'],
    'Norway' => ['Oslo', 'Bergen', 'Trondheim', 'Stavanger', 'Drammen', 'Regional Towns'],
    'Denmark' => ['Copenhagen', 'Aarhus', 'Odense', 'Aalborg', 'Esbjerg', 'Regional Towns']
];

function generateDatabase() {
    global $geography, $iso_map;
    $db = [];
    
    foreach($geography as $country => $cities) {
        $db[$country] = [];
        $countryPrefix = isset($iso_map[$country]) ? $iso_map[$country] : strtoupper(substr(str_replace(' ', '', $country), 0, 3));

        foreach($cities as $city) {
            $db[$country][$city] = [];
            mt_srand(crc32($city));
            
            if ($city === 'Regional Towns') {
                $districtPool = ['Suburban Aggregate', 'Rural Zone', 'Exurban District', 'Provincial Region', 'Outskirts'];
                $numDistricts = count($districtPool);
            } else {
                $districtPool = ['District 1', 'District 2', 'District 3', 'District 4', 'District 5', 'District 6', 'District 7', 'District 8'];
                $numDistricts = mt_rand(4, 7);
            }
            
            $shuffledTypes = $districtPool;
            shuffle_seeded($shuffledTypes);
            
            for($i = 0; $i < $numDistricts; $i++) {
                $distName = $shuffledTypes[$i];
                $cityPrefix = strtoupper(substr(str_replace(' ', '', $city), 0, 3));
                
                // Use D# format instead of Dis for District names
                if (preg_match('/District\s*(\d+)/i', $distName, $matches)) {
                    $distPrefix = 'D' . $matches[1];
                } else {
                    $distPrefix = strtoupper(substr(str_replace(' ', '', $distName), 0, 3));
                }
                
                // Advanced Ticker: Country-City-District (e.g. JPN-KYO-D8)
                $ticker = $countryPrefix . '-' . $cityPrefix . '-' . $distPrefix;
                
                $price = mt_rand(2500, 28000) + (mt_rand(0, 99) / 100);
                $changeVal = (mt_rand(-50, 50) / 10);
                $changeStr = ($changeVal > 0 ? '+' : '') . number_format($changeVal, 1) . '%';
                
                $db[$country][$city][$distName] = [
                    'ticker' => $ticker,
                    'price' => $price,
                    'change' => $changeStr
                ];
            }
        }
    }
    return $db;
}

function shuffle_seeded(&$items) {
    for ($i = count($items) - 1; $i > 0; $i--) {
        $j = mt_rand(0, $i);
        $tmp = $items[$i];
        $items[$i] = $items[$j];
        $items[$j] = $tmp;
    }
}

// Generates the hierarchical aggregate indices (Global -> Country -> City -> District)
function buildIndices(&$node, $level, $prefix) {
    $sum_price = 0;
    $sum_change = 0;
    $count = 0;
    
    foreach ($node as $key => &$child) {
        if (isset($child['ticker'])) {
            // Leaf node (District)
            $change_val = floatval(str_replace(['+', '%'], '', $child['change']));
            $sum_price += $child['price'];
            $sum_change += $change_val;
            $count++;
        } else {
            // Branch node (Country or City)
            global $iso_map;
            $next_prefix = '';
            if ($level === 0) { // Global -> Country
                $next_prefix = isset($iso_map[$key]) ? $iso_map[$key] : strtoupper(substr(str_replace(' ', '', $key), 0, 3));
            } else if ($level === 1) { // Country -> City
                $next_prefix = $prefix . '-' . strtoupper(substr(str_replace(' ', '', $key), 0, 3));
            }
            
            $child_meta = buildIndices($child, $level + 1, $next_prefix);
            $sum_price += $child_meta['price'];
            $sum_change += $child_meta['change_val'];
            $count++;
        }
    }
    
    $avg_price = $count > 0 ? $sum_price / $count : 0;
    $avg_change = $count > 0 ? $sum_change / $count : 0;
    
    $ticker = ($level === 0) ? 'REX-GLOBAL-IDX' : $prefix . '-IDX';
    
    $node['_meta'] = [
        'ticker' => $ticker,
        'price' => $avg_price,
        'change_val' => $avg_change,
        'change' => ($avg_change >= 0 ? '+' : '') . number_format($avg_change, 1) . '%'
    ];
    
    return $node['_meta'];
}

$database = generateDatabase();
// Precompute the indices for the entire database tree
buildIndices($database, 0, 'REX');

// ==========================================
// 2. ROUTING LOGIC & VIEW MANAGEMENT
// ==========================================
$view = isset($_GET['view']) ? $_GET['view'] : 'terminal';

$path = isset($_GET['path']) ? explode('|', $_GET['path']) : [];
$current_level_data = $database;
$trade_target = null; // Unified target for opening a trade/chart view
$node_type = count($path); 

// Build Back URL for the terminal
$back_url = "?view=terminal";
if ($node_type > 0) {
    $back_path = array_slice($path, 0, -1);
    if (count($back_path) > 0) {
        $back_url = '?view=terminal&path=' . urlencode(implode('|', $back_path));
    }
}

foreach ($path as $node) {
    if (isset($current_level_data[$node])) {
        $current_level_data = $current_level_data[$node];
    } else {
        $path = []; $current_level_data = $database; $node_type = 0; break;
    }
}

// Establish universal Trade Target (Allows buying full global indices, country indices, or districts)
if ($view === 'terminal') {
    if ($node_type === 3 && isset($current_level_data['ticker'])) {
        $trade_target = $current_level_data;
    } elseif (isset($current_level_data['_meta'])) {
        $trade_target = $current_level_data['_meta'];
    }
}

function buildPath($currentPath, $newNode) {
    return '?view=terminal&path=' . urlencode(implode('|', array_merge($currentPath, [$newNode])));
}

function getFlag($countryName) {
    global $flags;
    return isset($flags[$countryName]) ? $flags[$countryName] . ' ' : '';
}

// ==========================================
// 3. PROPERTY & DATA GENERATOR
// ==========================================
function generateProperties($ticker, $index_price) {
    $houses = [];
    $zones = ['Historic Core', 'Commercial Sector', 'Residential Zone A', 'North Sector', 'Waterfront', 'Transit Hub Zone'];
    $types = ['Penthouse', 'Apartment', 'Townhouse', 'Studio', 'Duplex', 'Loft'];
    
    $avg_home_val = $index_price * 100;
    
    mt_srand(crc32($ticker . "props")); 
    $num_houses = mt_rand(25, 60); 
    
    for($i = 0; $i < $num_houses; $i++) {
        $avm_val = $avg_home_val * (mt_rand(60, 150) / 100); 
        $tokenized_pct = mt_rand(5, 40);
        $equity_value = $avm_val * ($tokenized_pct / 100);
        $shares = $equity_value / $index_price;
        
        $houses[] = [
            'hash' => '0x' . strtoupper(substr(md5($ticker . $i), 0, 8)),
            'zone' => $zones[array_rand($zones)],
            'type' => $types[array_rand($types)],
            'avm' => $avm_val,
            'tokenized' => $tokenized_pct,
            'shares' => $shares
        ];
    }
    return $houses;
}

if ($trade_target && $view === 'terminal') {
    mt_srand(crc32($trade_target['ticker'] . "stats"));
    $market_heat_labels = ['Calm', 'Steady', 'Elevated', 'High Activity'];
    $heat_index = mt_rand(0, 3);
    $market_heat = $market_heat_labels[$heat_index];
    $heat_pct = 25 * ($heat_index + 1) - mt_rand(0, 10);
    
    $avg_tok = mt_rand(120, 350) / 10; 
    $short_int = mt_rand(20, 150) / 10; 
    $vol = mt_rand(1, 15) . "." . mt_rand(1, 9) . "M";
    
    // Only generate specific houses if looking at a district
    $district_houses = [];
    $active_nodes = 0;
    if ($node_type === 3) {
        $district_houses = generateProperties($trade_target['ticker'], $trade_target['price']);
        $active_nodes = count($district_houses);
    } else {
        $active_nodes = mt_rand(500, 15000); // Fictional large count for aggregate indices
    }

    // Generate 30 days of mock chart history for the target
    $chartData = [];
    $chartLabels = [];
    $base_price = $trade_target['price'] * (mt_rand(85, 110) / 100); 
    for ($d = 30; $d >= 0; $d--) {
        $chartLabels[] = date('M d', strtotime("-$d days"));
        $base_price = $base_price + mt_rand(-400, 450); 
        $chartData[] = round($base_price, 2);
    }
    $chartData[30] = $trade_target['price']; 
}

// ==========================================
// 4. PORTFOLIO DATA GENERATOR
// ==========================================
if ($view === 'portfolio') {
    // Gather some sample tickers for the portfolio
    $flat_tickers_port = [];
    foreach($database as $country => $cities) {
        if ($country === '_meta') continue;
        foreach($cities as $city => $districts) {
            if ($city === '_meta') continue;
            foreach($districts as $dist => $data) {
                if ($dist === '_meta') continue;
                $flat_tickers_port[] = $data['ticker'];
            }
        }
    }
    mt_srand(101010); // Fixed seed so portfolio looks stable across reloads
    shuffle($flat_tickers_port);
    
    $port_chart_labels = [];
    $port_chart_data = [];
    $base_val = 185000;
    for ($d = 30; $d >= 0; $d--) {
        $port_chart_labels[] = date('M d', strtotime("-$d days"));
        $base_val += mt_rand(-1500, 2200); 
        $port_chart_data[] = $base_val;
    }
    $current_portfolio_value = end($port_chart_data);
    $total_invested = 175000;
    $total_pl_pct = (($current_portfolio_value - $total_invested) / $total_invested) * 100;
    $total_pl_abs = $current_portfolio_value - $total_invested;
    $cash_balance = 24500.50;

    $portfolio_holdings = [
        ['date' => date('M d, Y', strtotime('-120 days')), 'ticker' => $flat_tickers_port[0], 'shares' => 15.5, 'avg_price' => 12500, 'current_price' => 13200],
        ['date' => date('M d, Y', strtotime('-85 days')), 'ticker' => $flat_tickers_port[1], 'shares' => 42.0, 'avg_price' => 5400, 'current_price' => 4900],
        ['date' => date('M d, Y', strtotime('-45 days')), 'ticker' => $flat_tickers_port[2], 'shares' => 8.25, 'avg_price' => 22000, 'current_price' => 24500],
        ['date' => date('M d, Y', strtotime('-15 days')), 'ticker' => $flat_tickers_port[3], 'shares' => 110.0, 'avg_price' => 1100, 'current_price' => 1250],
    ];

    $portfolio_trades = [
        ['date' => date('M d, Y', strtotime('-1 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[0], 'shares' => 5.0, 'price' => 13100],
        ['date' => date('M d, Y', strtotime('-3 days')), 'type' => 'SELL', 'ticker' => $flat_tickers_port[4], 'shares' => 12.0, 'price' => 8200],
        ['date' => date('M d, Y', strtotime('-5 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[2], 'shares' => 8.25, 'price' => 22000],
        ['date' => date('M d, Y', strtotime('-12 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[1], 'shares' => 42.0, 'price' => 5400],
        ['date' => date('M d, Y', strtotime('-15 days')), 'type' => 'VAULT LOCK', 'ticker' => $flat_tickers_port[3], 'shares' => 110.0, 'price' => 1100],
    ];
}

// Generate Mock Blockchain Data if View is 'chain'
$chain_blocks = [];
if ($view === 'chain') {
    // 1. Gather some random realistic tickers from the DB
    $flat_tickers = [];
    foreach($database as $country => $cities) {
        if ($country === '_meta') continue;
        foreach($cities as $city => $districts) {
            if ($city === '_meta') continue;
            foreach($districts as $dist => $data) {
                if ($dist === '_meta') continue;
                $flat_tickers[] = $data['ticker'];
            }
        }
    }
    mt_srand(time()); // randomize for live effect
    shuffle($flat_tickers);
    $sample_tickers = array_slice($flat_tickers, 0, 15);

    // 2. Create Genesis Block (Index 0)
    $chain_blocks[] = [
        'height' => 0,
        'hash' => '0x0000000000000000000000000000000000000000',
        'timestamp' => date('M d Y, H:i:s', strtotime('-30 days')),
        'type' => 'AMM Genesis Contract',
        'status' => 'Confirmed',
        'transactions' => [
            [
                'txid' => '0x'.substr(md5('genesis_tx'), 0, 16), 
                'type' => 'LIQUIDITY_ADD', 
                'detail' => 'Initial AMM Pool: $100,000,000 USD / 10,000,000 REX-SHARES'
            ]
        ]
    ];

    // 3. Create Recent Transaction Blocks
    $tx_types = ['BUY', 'SELL', 'SHORT', 'VAULT_LOCK'];
    $current_height = mt_rand(450000, 480000);

    for ($b = 1; $b <= 8; $b++) {
        $txs = [];
        $num_tx = mt_rand(1, 4);
        for ($t = 0; $t < $num_tx; $t++) {
            $t_type = $tx_types[array_rand($tx_types)];
            $t_tick = $sample_tickers[array_rand($sample_tickers)];
            $amount = mt_rand(100, 50000);
            
            if ($t_type == 'BUY') {
                $detail = "Bought $".number_format($amount)." of ".$t_tick;
                $color = "text-emerald-500 bg-emerald-500/10 border-emerald-500/20";
            } elseif ($t_type == 'SELL') {
                $detail = "Sold ".number_format($amount/100, 2)." shares of ".$t_tick;
                $color = "text-rose-500 bg-rose-500/10 border-rose-500/20";
            } elseif ($t_type == 'SHORT') {
                $detail = "Shorted $".number_format($amount)." of ".$t_tick;
                $color = "text-orange-500 bg-orange-500/10 border-orange-500/20";
            } else {
                $detail = "Locked ".number_format($amount/100, 2)." shares of ".$t_tick." (1Y)";
                $color = "text-purple-500 bg-purple-500/10 border-purple-500/20";
            }
            
            $txs[] = [
                'txid' => '0x'.substr(md5("tx".$b.$t.time()), 0, 16),
                'type' => $t_type,
                'color' => $color,
                'detail' => $detail
            ];
        }
        
        $chain_blocks[] = [
            'height' => $current_height + $b,
            'hash' => '0x'.substr(hash('sha256', "block".$b.time()), 0, 40),
            'timestamp' => date('M d Y, H:i:s', strtotime('-'.(8-$b).' minutes')),
            'type' => 'Standard Settlement',
            'status' => ($b === 8) ? 'Processing' : 'Confirmed',
            'transactions' => $txs
        ];
    }

    // Reverse to show newest at the top
    $chain_blocks = array_reverse($chain_blocks);
}
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REX | Global Asset Terminal</title>
    <!-- Injected Tailwind, Chart.js & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600;700&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: { 850: '#151e2e', 900: '#0f172a' },
                        parchment: { 100: '#f9f8f3', 200: '#f0ebd8' }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        handwriting: ['Caveat', 'cursive'],
                    }
                }
            }
        }
    </script>
    <style>
        /* ==========================================================
           PREMIUM GLASSMORPHISM DESIGN SYSTEM
           ========================================================== */
        :root {
            --brand-color: #2563eb; 
            --brand-hover: #1d4ed8;
            --brand-glow: rgba(37, 99, 235, 0.2);
            
            /* Light Theme: Frosted White Glass */
            --bg-body: rgba(248, 250, 252, 0.5); 
            --bg-pane: rgba(255, 255, 255, 0.65); 
            --bg-pane-hover: rgba(255, 255, 255, 0.8); 
            --bg-subtle: rgba(255, 255, 255, 0.4);
            --bg-stronger: rgba(0, 0, 0, 0.04);
            
            --text-main: #0f172a; 
            --text-muted: #475569; 
            --text-light: #94a3b8; 
            
            --accent-up: #059669; 
            --accent-down: #e11d48; 
            
            --border: rgba(255, 255, 255, 0.4); 
            --border-highlight: rgba(255, 255, 255, 1); 
            
            --shadow-sm: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 16px 48px rgba(0, 0, 0, 0.12);
            
            --radius-card: 24px; 
            --radius-pill: 999px;
            --blur-amount: 24px; 
        }

        .dark {
            /* Dark Theme: Obsidian/Slate Frosted Glass */
            --brand-color: #3b82f6; 
            --brand-hover: #60a5fa;
            --brand-glow: rgba(59, 130, 246, 0.25);
            
            --bg-body: rgba(15, 23, 42, 0.8); 
            --bg-pane: rgba(30, 41, 59, 0.55); 
            --bg-pane-hover: rgba(30, 41, 59, 0.75); 
            --bg-subtle: rgba(15, 23, 42, 0.4);
            --bg-stronger: rgba(255, 255, 255, 0.06);

            --text-main: #f8fafc; 
            --text-muted: #94a3b8; 
            --text-light: #64748b; 
            
            --accent-up: #10b981; 
            --accent-down: #f43f5e; 
            
            --border: rgba(255, 255, 255, 0.08); 
            --border-highlight: rgba(255, 255, 255, 0.15); 
            
            --shadow-sm: 0 8px 32px rgba(0, 0, 0, 0.3);
            --shadow-hover: 0 16px 48px rgba(0, 0, 0, 0.5);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            -webkit-font-smoothing: antialiased; 
            line-height: 1.5; 
            overflow-x: hidden; 
            min-height: 100vh;
            transition: background-color 0.4s, color 0.4s;
            font-size: 1.05rem; 
        }
        
        /* --------------------------------------
           BACKGROUND CANVAS 
           -------------------------------------- */
        .bg-canvas {
            position: fixed;
            top: -20vh; left: -20vw; width: 140vw; height: 140vh;
            z-index: -2;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #020617; 
        }
        .netflix-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
            transform: rotate(-18deg) scale(1.6);
            width: 160vw;
            filter: blur(4px);
            opacity: 0.5;
            transition: opacity 0.5s, filter 0.5s;
        }
        .dark .netflix-grid {
            opacity: 0.2;
            filter: blur(6px);
        }
        .netflix-img {
            width: 100%;
            aspect-ratio: 16 / 9;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            background-color: #334155; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.8);
        }
        .light-halo {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120vw; height: 120vh;
            background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 60%);
            z-index: -1;
            pointer-events: none;
        }
        .dark .light-halo {
            background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, rgba(15, 23, 42, 0) 70%);
        }

        /* --------------------------------------
           UNIVERSAL GLASS PANE CLASS
           -------------------------------------- */
        .glass-pane {
            background: var(--bg-pane);
            border: 1px solid var(--border);
            border-top: 1px solid var(--border-highlight);
            border-left: 1px solid var(--border-highlight);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(var(--blur-amount));
            -webkit-backdrop-filter: blur(var(--blur-amount));
            border-radius: var(--radius-card);
            overflow: hidden;
            position: relative;
        }

        /* --------------------------------------
           HEADER NAVIGATION PILLS
           -------------------------------------- */
        .header-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-subtle);
            padding: 6px;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .nav-btn {
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid transparent;
            background: transparent;
        }
        .nav-btn:hover {
            color: var(--text-main);
            background: var(--bg-pane);
            border-color: var(--border);
        }
        .nav-btn.active-terminal {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            border-color: #60a5fa;
        }
        .nav-btn.active-offer {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            border-color: #a78bfa;
        }
        .nav-btn.active-portfolio {
            background: linear-gradient(135deg, #059669, #047857);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4);
            border-color: #34d399;
        }
        .nav-btn.active-chain {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4);
            border-color: #fbbf24;
        }

        /* --------------------------------------
           MARKET TERMINAL UI COMPONENTS
           -------------------------------------- */
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }
        
        .menu-card { 
            padding: 36px 32px; 
            text-decoration: none; color: var(--text-main);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .menu-card:hover { 
            transform: translateY(-4px); 
            box-shadow: var(--shadow-hover); 
            background: var(--bg-pane-hover);
            border-color: var(--brand-glow);
        }
        .menu-card h3 { font-size: 22px; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.4px;}
        .menu-card p { font-size: 15px; color: var(--text-muted); font-weight: 500;}
        .card-action { margin-top: 36px; font-size: 15px; font-weight: 600; color: var(--brand-color); display: flex; align-items: center; gap: 8px; transition: gap 0.3s; }
        .menu-card:hover .card-action { gap: 12px; } 

        .dashboard-header { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 40px; }
        .metric-panel { padding: 40px; }
        .ticker-display { font-size: 15px; color: var(--text-muted); margin-bottom: 16px; font-weight: 600; display: flex; align-items: center; gap: 12px;}
        .ticker-badge { background: var(--brand-glow); padding: 6px 14px; border-radius: var(--radius-pill); color: var(--brand-color); font-weight: 700; border: 1px solid rgba(59,130,246,0.3);}
        .price-display { font-size: 56px; font-weight: 800; letter-spacing: -2px; margin-bottom: 12px; color: var(--text-main); line-height: 1;}
        .change-up { color: var(--accent-up); font-weight: 600; background: rgba(5, 150, 105, 0.1); padding: 8px 16px; border-radius: var(--radius-pill); display: inline-flex; align-items: center; gap: 6px; font-size: 15px;}
        .change-down { color: var(--accent-down); font-weight: 600; background: rgba(225, 29, 72, 0.1); padding: 8px 16px; border-radius: var(--radius-pill); display: inline-flex; align-items: center; gap: 6px; font-size: 15px;}
        .mini-stats { display: flex; gap: 56px; margin-top: 40px; padding-top: 40px; border-top: 1px solid var(--border); }
        .mini-stat-box { display: flex; flex-direction: column; gap: 10px; }
        .mini-stat-label { font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;}
        .mini-stat-val { font-size: 22px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;}
        .heat-bar-container { margin-top: 20px; background: var(--bg-stronger); height: 10px; border-radius: var(--radius-pill); overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .heat-bar-fill { height: 100%; background: linear-gradient(90deg, var(--brand-color), #60a5fa); border-radius: var(--radius-pill); transition: width 1.2s cubic-bezier(0.16, 1, 0.3, 1); }

        .data-panel-header { padding: 32px 40px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-subtle);}
        .data-panel-title { font-size: 20px; font-weight: 700; color: var(--text-main); letter-spacing: -0.4px;}
        
        .breadcrumb-link { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        .breadcrumb-link:hover { color: var(--brand-color); }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 24px 40px; background: var(--bg-stronger); font-size: 13px; font-weight: 700; color: var(--text-muted); border-bottom: 1px solid var(--border); text-transform: uppercase; letter-spacing: 1px;}
        td { padding: 28px 40px; font-size: 16px; border-bottom: 1px solid var(--border); color: var(--text-main); font-weight: 500; transition: background 0.2s;}
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-subtle); }
        
        .hash-col { color: var(--text-light); font-weight: 400; font-family: 'Menlo', 'Monaco', monospace; font-size: 14px;}
        .tag { background: var(--bg-stronger); border: 1px solid var(--border); padding: 8px 14px; border-radius: var(--radius-pill); font-size: 13px; font-weight: 600; color: var(--text-muted);}
        .shares-badge { color: var(--brand-color); font-weight: 700; font-size: 16px;}

        .btn-trade { background: var(--brand-color); color: white; border: none; padding: 14px 32px; border-radius: var(--radius-pill); font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 4px 12px var(--brand-glow); }
        .btn-trade:hover { background: var(--brand-hover); transform: translateY(-2px); box-shadow: 0 8px 20px var(--brand-glow);}

        /* --------------------------------------
           SIDE PANEL TRADE UI
           -------------------------------------- */
        .overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.4); backdrop-filter: blur(8px);
            z-index: 999; opacity: 0; pointer-events: none; transition: opacity 0.4s ease;
        }
        .overlay.active { opacity: 1; pointer-events: auto; }

        .side-panel {
            position: fixed; top: 0; right: -520px; width: 520px; height: 100vh;
            background: var(--bg-pane); z-index: 1000; box-shadow: -10px 0 40px rgba(0,0,0,0.2);
            transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column; border-left: 1px solid var(--border-highlight);
            backdrop-filter: blur(32px); -webkit-backdrop-filter: blur(32px);
        }
        .side-panel.active { right: 0; }
        
        .panel-header { padding: 40px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-subtle);}
        .panel-title { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px;}
        .panel-title-badge { font-size: 14px; background: var(--brand-glow); color: var(--brand-color); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3);}
        
        .btn-close {
            background: var(--bg-stronger); color: var(--text-main); border: 1px solid var(--border); 
            width: 40px; height: 40px; border-radius: 50%; font-size: 20px; 
            cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .btn-close:hover { background: var(--bg-subtle); transform: rotate(90deg); }

        .trade-tabs { display: flex; border-bottom: 1px solid var(--border); padding: 0 40px;}
        .trade-tab {
            padding: 24px 0; margin-right: 40px; color: var(--text-muted); font-weight: 600; font-size: 16px;
            cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s;
        }
        .trade-tab:hover { color: var(--text-main); }
        .trade-tab.active { color: var(--brand-color); border-bottom-color: var(--brand-color); }

        .tab-content { padding: 40px; display: none; flex: 1; overflow-y: auto;}
        .tab-content.active { display: block; }

        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 15px; color: var(--text-muted); margin-bottom: 12px; font-weight: 600;}
        .form-input-wrapper { position: relative; display: flex; align-items: center; }
        .form-input-wrapper span { position: absolute; left: 20px; color: var(--text-muted); font-size: 20px; font-weight: 700;}
        
        .form-group input {
            width: 100%; background: var(--bg-subtle); border: 1px solid var(--border);
            color: var(--text-main); font-size: 28px; font-weight: 700;
            padding: 16px 20px 16px 48px; border-radius: 16px; outline: none; transition: all 0.2s;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .form-group input:focus { border-color: var(--brand-color); background: var(--bg-pane); box-shadow: 0 0 0 4px var(--brand-glow), inset 0 2px 4px rgba(0,0,0,0.05); }

        .btn-submit {
            width: 100%; background: var(--brand-color); color: white; border: none;
            padding: 20px; border-radius: 16px; font-size: 18px; font-weight: 700;
            cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 24px var(--brand-glow);
        }
        .btn-submit:hover { background: var(--brand-hover); transform: translateY(-2px); }
        .btn-submit.btn-offer { background: var(--text-main); color: var(--bg-body); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .btn-submit.btn-offer:hover { opacity: 0.9; }

        .offer-text { font-size: 16px; color: var(--text-muted); line-height: 1.7; margin-bottom: 40px; }

        /* --------------------------------------
           CHART PANEL UI
           -------------------------------------- */
        .chart-panel {
            position: fixed; 
            top: 104px; 
            left: -100vw; 
            width: calc(100vw - 568px); 
            height: calc(100vh - 128px);
            background: var(--bg-pane); 
            z-index: 1001; 
            box-shadow: var(--shadow-hover);
            transition: left 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid var(--border);
            border-top: 1px solid var(--border-highlight);
            border-left: 1px solid var(--border-highlight);
            border-radius: var(--radius-card);
            backdrop-filter: blur(var(--blur-amount));
            -webkit-backdrop-filter: blur(var(--blur-amount));
            padding: 36px 48px;
            display: flex;
            flex-direction: column;
        }
        
        .chart-panel.active { left: 24px; }

        @media (max-width: 1200px) {
            .chart-panel { display: none !important; }
        }
        
        /* --------------------------------------
           BLOCKCHAIN LEDGER STYLING
           -------------------------------------- */
        .chain-timeline { position: relative; padding-left: 48px; }
        .chain-timeline::before {
            content: ''; position: absolute; left: 16px; top: 0; bottom: 0;
            width: 4px; background: var(--border); border-radius: 4px;
        }
        .block-node {
            position: absolute; left: -44px; top: 32px;
            width: 24px; height: 24px; border-radius: 50%;
            background: var(--brand-color); border: 4px solid var(--bg-pane);
            box-shadow: 0 0 0 4px var(--brand-glow);
            z-index: 10;
        }
        .genesis-node { background: #059669; box-shadow: 0 0 0 4px rgba(5,150,105,0.2); }
        
        .chain-block { transition: transform 0.2s; }
        .chain-block:hover { transform: translateX(8px); }

    </style>
</head>
<body>

    <!-- Subtle Halo Lighting & Background Grid -->
    <div class="light-halo"></div>
    <div class="bg-canvas">
        <div class="netflix-grid">
            <?php 
                $houses = [];
                for ($i = 1; $i <= 11; $i++) { $houses[] = "house$i.jpg"; }
                $grid_images = array_merge($houses, $houses, $houses, $houses, $houses, $houses);
                foreach ($grid_images as $img): 
            ?>
            <div class="netflix-img" style="background-image: url('<?php echo $img; ?>');"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Header with Navigation Pills -->
    <header class="fixed top-0 w-full z-50 p-5 flex justify-between items-center bg-white/60 dark:bg-slate-900/60 backdrop-blur-xl border-b border-white/40 dark:border-slate-700/50 transition-colors shadow-sm">
        
        <!-- Left Side: REX Logo -->
        <a href="?view=terminal" class="flex items-center gap-3 relative z-10 no-underline cursor-pointer transition-transform hover:scale-[1.02]">
            <img src="rex_logo.png" alt="REX Logo" class="h-10 w-auto drop-shadow-sm" onerror="this.style.display='none'">
            <div class="flex items-center font-serif text-xl tracking-wide text-slate-900 dark:text-white drop-shadow-sm whitespace-nowrap">
                <span class="font-black text-2xl tracking-[0.1em] bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-500 dark:from-slate-100 dark:to-slate-400">REX&reg; - </span> 
                <span class="hidden md:inline font-semibold opacity-90 ml-2">Residential Property Exchange</span>
            </div>
        </a>
        
        <!-- Center Navigation Pane -->
        <div class="absolute left-1/2 transform -translate-x-1/2 hidden xl:flex items-center z-0">
            <nav class="header-nav">
                <a href="?view=terminal" class="nav-btn <?= $view === 'terminal' ? 'active-terminal' : '' ?>">Global Asset Terminal</a>
                <a href="?view=offer" class="nav-btn <?= $view === 'offer' ? 'active-offer' : '' ?>">Offer Home Equity</a>
                <a href="?view=portfolio" class="nav-btn <?= $view === 'portfolio' ? 'active-portfolio' : '' ?>">My Portfolio</a>
                <a href="?view=chain" class="nav-btn <?= $view === 'chain' ? 'active-chain' : '' ?>">View Transaction Chain</a>
            </nav>
        </div>

        <!-- Right Side: Theme Toggle -->
        <div class="flex items-center gap-6 relative z-10">
            <button onclick="toggleDarkMode()" class="p-2.5 rounded-full bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-700/80 transition-colors border border-white/50 dark:border-slate-600/50 shadow-sm" title="Toggle Theme">
                <svg id="icon-sun" class="w-6 h-6 hidden dark:block text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="icon-moon" class="w-6 h-6 block dark:hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
        </div>
    </header>

    <main class="pt-36 pb-24 px-6 sm:px-8 lg:px-12 max-w-[1500px] mx-auto relative z-10">
        
        <?php if ($view === 'terminal'): ?>
            <!-- Premium Glass Breadcrumbs & Back Button -->
            <div class="flex items-center gap-4 mb-12">
                <?php if ($node_type > 0): ?>
                    <a href="<?= $back_url ?>" class="glass-pane px-5 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-brand-color dark:hover:text-brand-color transition-colors inline-flex items-center gap-2 border-r-2" style="border-radius: 12px;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back
                    </a>
                <?php endif; ?>
                
                <div class="glass-pane px-8 py-3 text-base font-semibold inline-flex items-center flex-wrap" style="border-radius: 12px;">
                    <a href="?view=terminal" class="breadcrumb-link">Global Markets</a>
                    <?php
                        $build_path = [];
                        foreach ($path as $index => $node) {
                            $build_path[] = $node;
                            $displayName = $node;
                            if ($index === 0) $displayName = getFlag($node) . $displayName;
                            echo '<span class="mx-4 opacity-40" style="color: var(--text-muted)">/</span> <a href="?view=terminal&path=' . urlencode(implode('|', $build_path)) . '" class="breadcrumb-link">' . htmlspecialchars($displayName) . '</a>';
                        }
                    ?>
                </div>
            </div>

            <?php if ($node_type === 3 && $trade_target): ?>
                
                <?php 
                    $is_up = strpos($trade_target['change'], '+') !== false;
                    $change_class = $is_up ? 'change-up' : 'change-down';
                    $arrow = $is_up ? '↑' : '↓';
                ?>

                <div class="dashboard-header">
                    <div class="glass-pane metric-panel">
                        <div class="ticker-display">Market Identifier <span class="ticker-badge"><?= $trade_target['ticker'] ?></span></div>
                        <div class="price-display">$<?= number_format($trade_target['price'], 2) ?></div>
                        <div class="<?= $change_class ?>">
                            <?= $arrow ?> <?= $trade_target['change'] ?> Past 24h
                        </div>
                        <div class="mini-stats">
                            <div class="mini-stat-box">
                                <span class="mini-stat-label">24h Trading Volume</span>
                                <span class="mini-stat-val">$<?= $vol ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="glass-pane metric-panel">
                        <div style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 28px; letter-spacing: -0.4px;">Market Activity</div>
                        <div class="mini-stat-box" style="margin-bottom: 40px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                <span class="mini-stat-label">Liquidity Depth</span>
                                <span style="font-size: 15px; font-weight: 800; color: var(--brand-color);"><?= strtoupper($market_heat) ?></span>
                            </div>
                            <div class="heat-bar-container"><div class="heat-bar-fill" style="width: <?= $heat_pct ?>%;"></div></div>
                        </div>
                        <div class="mini-stats" style="border: none; padding: 0; margin: 0; gap: 56px;">
                            <div class="mini-stat-box">
                                <span class="mini-stat-label">Tokenized Avg</span>
                                <span class="mini-stat-val"><?= $avg_tok ?>%</span>
                            </div>
                            <div class="mini-stat-box">
                                <span class="mini-stat-label">Active Homes</span>
                                <span class="mini-stat-val"><?= number_format($active_nodes) ?></span>
                            </div>
                            <div class="mini-stat-box">
                                <span class="mini-stat-label">Short Interest</span>
                                <span class="mini-stat-val" style="color: var(--text-muted);"><?= $short_int ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-pane data-panel">
                    <div class="data-panel-header">
                        <div class="data-panel-title">Properties Backing the Index</div>
                        <button class="btn-trade shadow-lg" onclick="openPanel()">Trade <?= $trade_target['ticker'] ?></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Asset Hash ID</th>
                                <th>Neighborhood Zone</th>
                                <th>Property Class</th>
                                <th>Estimated Valuation</th>
                                <th>Tokenized %</th>
                                <th>Shares Offered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($district_houses as $home): ?>
                            <tr>
                                <td class="hash-col"><?= $home['hash'] ?></td>
                                <td><?= $home['zone'] ?></td>
                                <td><span class="tag"><?= $home['type'] ?></span></td>
                                <td style="font-weight: 600;">$<?= number_format($home['avm']) ?></td>
                                <td style="color: var(--text-muted); font-weight: 600;"><?= $home['tokenized'] ?>%</td>
                                <td><span class="shares-badge"><?= number_format($home['shares'], 2) ?> Shares</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="glass-pane px-10 py-6 inline-block">
                        <div style="font-size: 36px; font-weight: 800; color: var(--text-main); font-family: 'Playfair Display', serif; letter-spacing: -0.5px;">
                            <?php 
                                if($node_type === 0) echo "Explore Global Markets";
                                elseif($node_type === 1) echo getFlag(end($path)) . " Cities in " . end($path);
                                elseif($node_type === 2) echo "Districts in " . end($path);
                            ?>
                        </div>
                    </div>
                    
                    <?php if (isset($current_level_data['_meta'])): ?>
                        <?php 
                            $meta = $current_level_data['_meta']; 
                            $is_up = $meta['change_val'] >= 0;
                            $c_color = $is_up ? 'text-emerald-500 bg-emerald-500/10 border border-emerald-500/20' : 'text-rose-500 bg-rose-500/10 border border-rose-500/20';
                        ?>
                        <div class="flex items-center gap-8">
                            <div class="text-left md:text-right">
                                <div class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Aggregate Index • <?= $meta['ticker'] ?></div>
                                <div class="text-3xl font-black text-slate-900 dark:text-white font-mono flex items-center gap-4">
                                    $<?= number_format($meta['price'], 2) ?>
                                    <span class="text-lg px-3 py-1 rounded-full <?= $c_color ?>"><?= $meta['change'] ?></span>
                                </div>
                            </div>
                            <button class="btn-trade shadow-lg" onclick="openPanel()">Trade <?= $meta['ticker'] ?></button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="grid-menu">
                    <?php foreach ($current_level_data as $key => $value): ?>
                        <?php if ($key === '_meta') continue; ?>
                        <a href="<?= buildPath($path, $key) ?>" class="glass-pane menu-card">
                            <div>
                                <h3>
                                    <?php 
                                        if($node_type === 0) echo getFlag($key); 
                                        echo htmlspecialchars($key); 
                                    ?>
                                </h3>
                                
                                <?php if($node_type === 0 || $node_type === 1): ?>
                                    <?php 
                                        $meta = $value['_meta'];
                                        $is_up = $meta['change_val'] >= 0;
                                        $c_color = $is_up ? 'text-emerald-500' : 'text-rose-500';
                                        $child_count = count($value) - 1; // subtract _meta
                                    ?>
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mt-2 mb-1">
                                        <?= $node_type === 0 ? $child_count . ' Cities' : $child_count . ' Districts' ?>
                                    </p>
                                    <p style="font-family: 'Menlo', 'Monaco', monospace; margin-top: 10px; font-size: 15px;">
                                        <span class="font-bold text-slate-800 dark:text-slate-200"><?= $meta['ticker'] ?></span><br>
                                        <span class="font-bold text-lg text-slate-900 dark:text-white">$<?= number_format($meta['price'], 2) ?></span> 
                                        <span class="<?= $c_color ?> font-semibold ml-2"><?= $meta['change'] ?></span>
                                    </p>
                                
                                <?php elseif($node_type === 2): ?>
                                    <p style="font-family: 'Menlo', 'Monaco', monospace; color: var(--brand-color); margin-top: 10px; font-weight: 600; font-size: 16px;">
                                        <?= $value['ticker'] ?> • $<?= number_format($value['price'], 2) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <span class="card-action">View Markets &rarr;</span>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <!-- Trade Overlay & Side Panel (Global for any Terminal View) -->
            <?php if ($trade_target): ?>
                <div class="overlay" id="overlay" onclick="closePanel()"></div>
                
                <div class="side-panel" id="tradePanel">
                    <div class="panel-header">
                        <div class="panel-title">Trade <span class="panel-title-badge"><?= $trade_target['ticker'] ?></span></div>
                        <button class="btn-close" onclick="closePanel()">×</button>
                    </div>
                    
                    <div class="trade-tabs">
                        <div class="trade-tab active" onclick="switchTab('buy')">Buy</div>
                        <div class="trade-tab" onclick="switchTab('sell')">Sell</div>
                        <div class="trade-tab" onclick="switchTab('short')">Short</div>
                        <div class="trade-tab" onclick="switchTab('vault')">Vault</div>
                        <div class="trade-tab" onclick="switchTab('offer')">Offer</div>
                    </div>

                    <!-- Buy Tab -->
                    <div id="tab-buy" class="tab-content active">
                        <div class="form-group">
                            <label>Spend Amount (USD)</label>
                            <div class="form-input-wrapper">
                                <span>$</span>
                                <input type="number" id="input-buy-usd" placeholder="0.00" oninput="syncInputs('buy', 'usd')">
                            </div>
                        </div>
                        <div class="text-center mb-6"><span style="color: var(--border); font-size: 24px; font-weight: 800;">⇌</span></div>
                        <div class="form-group" style="margin-bottom: 48px;">
                            <label>Receive Shares</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-buy-shares" placeholder="0.00" oninput="syncInputs('buy', 'shares')">
                            </div>
                        </div>
                        <button class="btn-submit" onclick="alert('Simulation: Buy order routed to market.')">Review Buy Order</button>
                    </div>

                    <!-- Sell Tab -->
                    <div id="tab-sell" class="tab-content">
                        <div class="form-group">
                            <label>Receive Amount (USD)</label>
                            <div class="form-input-wrapper">
                                <span>$</span>
                                <input type="number" id="input-sell-usd" placeholder="0.00" oninput="syncInputs('sell', 'usd')">
                            </div>
                        </div>
                        <div class="text-center mb-6"><span style="color: var(--border); font-size: 24px; font-weight: 800;">⇌</span></div>
                        <div class="form-group" style="margin-bottom: 48px;">
                            <label>Sell Shares</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-sell-shares" placeholder="0.00" oninput="syncInputs('sell', 'shares')">
                            </div>
                        </div>
                        <button class="btn-submit" onclick="alert('Simulation: Sell order routed to market.')">Review Sell Order</button>
                    </div>

                    <!-- Short Tab -->
                    <div id="tab-short" class="tab-content">
                        <div class="form-group">
                            <label>Initial Margin Deposit (50%)</label>
                            <div class="form-input-wrapper">
                                <span>$</span>
                                <input type="number" id="input-short-margin" placeholder="0.00" oninput="syncInputs('short', 'margin')">
                            </div>
                        </div>
                        <div class="text-center mb-6"><span style="color: var(--border); font-size: 24px; font-weight: 800;">⇌</span></div>
                        <div class="form-group" style="margin-bottom: 24px;">
                            <label>Borrowed Shares</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-short-shares" placeholder="0.00" oninput="syncInputs('short', 'shares')">
                            </div>
                        </div>

                        <!-- Margin Requirements UI -->
                        <div class="p-5 rounded-xl border border-rose-500/30 bg-rose-500/5 mb-8 text-sm">
                            <div class="flex justify-between mb-3 items-center">
                                <span class="text-slate-600 dark:text-slate-300 font-semibold">Total Position Size</span>
                                <span class="font-bold text-slate-900 dark:text-white text-lg" id="short-total-size">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600 dark:text-slate-300 font-semibold">Maintenance Margin (20%)</span>
                                <span class="font-bold text-slate-900 dark:text-white" id="short-maint-margin">$0.00</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-semibold leading-relaxed">
                                * You must deposit the 50% Initial Margin to open the position. If your equity falls below the Maintenance Margin, you may be liquidated.
                            </div>
                        </div>

                        <button class="btn-submit" style="background: var(--accent-down); box-shadow: 0 8px 24px rgba(225, 29, 72, 0.25);" onclick="alert('Simulation: Margin deposited. Short initialized.')">Deposit Margin & Short</button>
                    </div>

                    <!-- Vault (Lockup) Tab -->
                    <div id="tab-vault" class="tab-content">
                        <div class="form-group">
                            <label>Amount to Lock (Shares)</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-vault-shares" placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 40px;">
                            <label class="flex justify-between items-center mb-3">
                                <span>Lockup Period</span>
                            </label>
                            <select class="w-full bg-[var(--bg-subtle)] border border-[var(--border)] text-[var(--text-main)] font-semibold p-4 rounded-xl outline-none focus:border-[var(--brand-color)] transition-all">
                                <option value="1w">1 Week Lockup</option>
                                <option value="1m">1 Month Lockup</option>
                                <option value="1y">1 Year Lockup</option>
                            </select>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-3 font-medium">
                                <span class="text-rose-500 font-bold">*</span> Early withdrawal incurs a 2% penalty fee.
                            </p>
                        </div>

                        <button class="btn-submit" style="background: var(--brand-color);" onclick="alert('Simulation: Shares locked in REX Vault.')">Lock Shares</button>
                    </div>

                    <!-- Offer Tab linking to Questionnaire -->
                    <div id="tab-offer" class="tab-content">
                        <div class="offer-text">
                            <strong class="text-xl text-slate-900 dark:text-white mb-2 block">Tokenize Your Equity</strong>
                            <?php 
                                $location_name = end($path) ?: 'Global Market'; 
                                if ($node_type === 0) $location_name = 'Global Market';
                                elseif ($node_type === 1) $location_name = end($path) . ' Region';
                                elseif ($node_type === 2) $location_name = end($path) . ' Metro Area';
                            ?>
                            Do you own real estate in the <strong class="text-brand-color"><?= $location_name ?></strong>? REX allows you to mint new shares of <span class="text-brand-color font-bold"><?= $trade_target['ticker'] ?></span> backed by the legal equity in your home.<br><br>
                            To begin, complete the underwriting survey. We will algorithmically appraise your property to determine how many index shares you are eligible to offer on the terminal.
                        </div>
                        <button class="btn-submit btn-offer" onclick="window.location.href='REX_UI_Questionnaire.php'">Start Underwriting Survey &rarr;</button>
                    </div>
                </div>

                <!-- Dynamic Chart Window Elevated Above Overlay -->
                <div class="chart-panel" id="chartPanel">
                    <button class="btn-close" style="position: absolute; top: 24px; right: 24px;" onclick="closeChartPanel()">×</button>
                    <div class="flex justify-between items-center mb-8 pr-16">
                        <div>
                            <h3 class="font-bold text-3xl text-slate-900 dark:text-white tracking-tight mb-1"><?= $trade_target['ticker'] ?></h3>
                            <p class="text-base font-semibold" style="color: var(--text-muted)">Live Market Trajectory</p>
                        </div>
                        <span class="text-sm font-bold text-brand-color bg-blue-500/10 px-5 py-2.5 rounded-full border border-blue-500/20">30-Day History</span>
                    </div>
                    <div class="flex-1 relative w-full h-full">
                        <canvas id="marketChart"></canvas>
                    </div>
                </div>

                <script>
                    const indexPrice = <?= $trade_target['price'] ?>;

                    // Sync function for bidirectional inputs
                    function syncInputs(type, source) {
                        if (type === 'short') {
                            const marginInput = document.getElementById('input-short-margin');
                            const sharesInput = document.getElementById('input-short-shares');
                            let totalPosition = 0;
                            
                            if (source === 'margin') {
                                if (marginInput.value !== '') {
                                    totalPosition = parseFloat(marginInput.value) * 2; 
                                    sharesInput.value = (totalPosition / indexPrice).toFixed(4);
                                } else {
                                    sharesInput.value = '';
                                }
                            } else if (source === 'shares') {
                                if (sharesInput.value !== '') {
                                    totalPosition = parseFloat(sharesInput.value) * indexPrice;
                                    marginInput.value = (totalPosition * 0.50).toFixed(2); 
                                } else {
                                    marginInput.value = '';
                                }
                            }

                            document.getElementById('short-total-size').innerText = '$' + totalPosition.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            document.getElementById('short-maint-margin').innerText = '$' + (totalPosition * 0.20).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            return; 
                        }

                        const usdInput = document.getElementById('input-' + type + '-usd');
                        const sharesInput = document.getElementById('input-' + type + '-shares');
                        
                        if (source === 'usd') {
                            if (usdInput.value !== '') sharesInput.value = (usdInput.value / indexPrice).toFixed(4);
                            else sharesInput.value = '';
                        } else {
                            if (sharesInput.value !== '') usdInput.value = (sharesInput.value * indexPrice).toFixed(2);
                            else usdInput.value = '';
                        }
                    }

                    function openPanel() {
                        document.getElementById('overlay').classList.add('active');
                        document.getElementById('tradePanel').classList.add('active');
                        
                        const chartPanel = document.getElementById('chartPanel');
                        if (chartPanel) {
                            chartPanel.classList.add('active');
                        }
                    }

                    function closePanel() {
                        document.getElementById('overlay').classList.remove('active');
                        document.getElementById('tradePanel').classList.remove('active');
                        
                        const chartPanel = document.getElementById('chartPanel');
                        if (chartPanel) {
                            chartPanel.classList.remove('active');
                        }
                    }

                    function closeChartPanel() {
                        document.getElementById('chartPanel').classList.remove('active');
                    }

                    function switchTab(tabId) {
                        document.querySelectorAll('.trade-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                        
                        event.currentTarget.classList.add('active');
                        document.getElementById('tab-' + tabId).classList.add('active');
                    }

                    // Chart.js Init
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('marketChart');
                        if(ctx) {
                            Chart.defaults.color = '#94a3b8';
                            Chart.defaults.font.family = 'Inter, sans-serif';
                            
                            new Chart(ctx.getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: <?= json_encode($chartLabels) ?>,
                                    datasets: [{
                                        label: 'Index Price (USD)',
                                        data: <?= json_encode($chartData) ?>,
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.3,
                                        pointRadius: 0,
                                        pointHitRadius: 15,
                                        pointHoverRadius: 6,
                                        pointHoverBackgroundColor: '#ffffff',
                                        pointHoverBorderColor: '#3b82f6',
                                        pointHoverBorderWidth: 2,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: { intersect: false, mode: 'index' },
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            titleFont: { size: 13, weight: 'bold' },
                                            bodyFont: { size: 14, weight: 'bold' },
                                            padding: 12,
                                            cornerRadius: 8,
                                            displayColors: false,
                                            callbacks: {
                                                label: function(context) {
                                                    return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: { display: true, grid: { display: false }, ticks: { maxTicksLimit: 6, font: {weight: '600'} } },
                                        y: { display: true, border: { display: false }, grid: { color: 'rgba(148, 163, 184, 0.15)' }, ticks: { font: {weight: '600'}, callback: function(value) { return '$' + value; } } }
                                    }
                                }
                            });
                        }
                    });
                </script>
            <?php endif; ?>

        <?php elseif ($view === 'offer'): ?>
            
            <!-- OFFER HOME EQUITY VIEW -->
            <div class="glass-pane max-w-4xl mx-auto p-12 sm:p-16 text-center mt-12">
                <div class="w-20 h-20 bg-purple-500/10 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-8 border border-purple-500/20">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-6">Offer Home Equity</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Begin the algorithmic underwriting process to tokenize your property. Unlock liquidity by converting your physical real estate into tradable index shares on the REX network.
                </p>
                <button class="btn-trade" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 8px 24px rgba(139, 92, 246, 0.3); font-size: 18px; padding: 18px 40px;" onclick="window.location.href='REX_UI_Questionnaire.php'">Initialize Underwriting &rarr;</button>
            </div>

        <?php elseif ($view === 'portfolio'): ?>
            
            <!-- MY PORTFOLIO VIEW -->
            <div class="max-w-6xl mx-auto mt-8">
                
                <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div class="glass-pane px-10 py-6 inline-block">
                        <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-2">Portfolio Overview</h2>
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                            <p class="text-slate-600 dark:text-slate-400 font-mono text-sm tracking-wide">Connected: 0x7F4...3A9B</p>
                        </div>
                    </div>
                </div>

                <!-- Top Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="glass-pane p-8">
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Total Equity Value</div>
                        <div class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">$<?= number_format($current_portfolio_value, 2) ?></div>
                    </div>
                    <div class="glass-pane p-8">
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">All-Time P/L</div>
                        <div class="text-3xl font-bold tracking-tight <?= $total_pl_pct >= 0 ? 'text-emerald-500' : 'text-rose-500' ?>">
                            <?= $total_pl_pct >= 0 ? '+' : '' ?>$<?= number_format($total_pl_abs, 2) ?> (<?= number_format($total_pl_pct, 2) ?>%)
                        </div>
                    </div>
                    <div class="glass-pane p-8">
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Available Cash (USD)</div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">$<?= number_format($cash_balance, 2) ?></div>
                    </div>
                </div>

                <!-- Chart Pane -->
                <div class="glass-pane p-8 mb-10 h-[400px] flex flex-col">
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6">Historical Account Value</h3>
                    <div class="flex-1 relative w-full h-full">
                        <canvas id="portfolioChart"></canvas>
                    </div>
                </div>

                <!-- Clean Stacking Layout for Ledgers -->
                <div class="flex flex-col gap-10">
                    
                    <!-- Current Holdings -->
                    <div class="glass-pane p-8">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 border-b border-[var(--border)] pb-4">Current Holdings</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date Acquired</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Asset</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Shares</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Price (Avg / Live)</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Live P/L</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    <?php foreach ($portfolio_holdings as $holding): 
                                        $pl_val = ($holding['current_price'] - $holding['avg_price']) * $holding['shares'];
                                        $pl_pct = (($holding['current_price'] - $holding['avg_price']) / $holding['avg_price']) * 100;
                                    ?>
                                    <tr>
                                        <td class="py-4 text-sm font-semibold text-slate-500"><?= $holding['date'] ?></td>
                                        <td class="py-4 font-bold text-slate-900 dark:text-white"><?= $holding['ticker'] ?></td>
                                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-300"><?= number_format($holding['shares'], 2) ?></td>
                                        <td class="py-4">
                                            <div class="text-sm font-mono text-slate-500">Avg: $<?= number_format($holding['avg_price'], 2) ?></div>
                                            <div class="text-sm font-mono font-bold text-slate-900 dark:text-white">Live: $<?= number_format($holding['current_price'], 2) ?></div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="font-bold <?= $pl_val >= 0 ? 'text-emerald-500' : 'text-rose-500' ?>">
                                                <?= $pl_val >= 0 ? '+' : '' ?>$<?= number_format($pl_val, 2) ?>
                                            </div>
                                            <div class="text-sm font-semibold <?= $pl_pct >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                                                <?= $pl_pct >= 0 ? '+' : '' ?><?= number_format($pl_pct, 2) ?>%
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Trades -->
                    <div class="glass-pane p-8">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 border-b border-[var(--border)] pb-4">Recent Trades</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Asset</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Shares</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Price Executed</th>
                                        <th class="pb-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    <?php foreach ($portfolio_trades as $trade): ?>
                                    <tr>
                                        <td class="py-4 text-sm font-semibold text-slate-500"><?= $trade['date'] ?></td>
                                        <td class="py-4">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded border 
                                                <?= $trade['type'] == 'BUY' ? 'text-emerald-500 border-emerald-500/30 bg-emerald-500/10' : 
                                                   ($trade['type'] == 'SELL' ? 'text-rose-500 border-rose-500/30 bg-rose-500/10' : 
                                                   'text-purple-500 border-purple-500/30 bg-purple-500/10') ?>">
                                                <?= $trade['type'] ?>
                                            </span>
                                        </td>
                                        <td class="py-4 font-bold text-slate-900 dark:text-white"><?= $trade['ticker'] ?></td>
                                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-300"><?= number_format($trade['shares'], 2) ?></td>
                                        <td class="py-4 font-mono text-sm text-slate-500">$<?= number_format($trade['price'], 2) ?></td>
                                        <td class="py-4 text-right font-bold text-slate-900 dark:text-white">
                                            $<?= number_format($trade['shares'] * $trade['price'], 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('portfolioChart');
                    if(ctx) {
                        Chart.defaults.color = '#94a3b8';
                        Chart.defaults.font.family = 'Inter, sans-serif';
                        
                        new Chart(ctx.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: <?= json_encode($port_chart_labels) ?>,
                                datasets: [{
                                    label: 'Account Value (USD)',
                                    data: <?= json_encode($port_chart_data) ?>,
                                    borderColor: '#10b981', // Emerald green to represent growth
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 0,
                                    pointHitRadius: 15,
                                    pointHoverRadius: 6,
                                    pointHoverBackgroundColor: '#ffffff',
                                    pointHoverBorderColor: '#10b981',
                                    pointHoverBorderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { intersect: false, mode: 'index' },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 13, weight: 'bold' },
                                        bodyFont: { size: 14, weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: { display: true, grid: { display: false }, ticks: { maxTicksLimit: 6, font: {weight: '600'} } },
                                    y: { display: true, border: { display: false }, grid: { color: 'rgba(148, 163, 184, 0.15)' }, ticks: { font: {weight: '600'}, callback: function(value) { return '$' + value; } } }
                                }
                            }
                        });
                    }
                });
            </script>

        <?php elseif ($view === 'chain'): ?>
            
            <!-- TRANSACTION CHAIN LEDGER -->
            <div class="max-w-5xl mx-auto mt-8">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
                    <div class="glass-pane px-10 py-6 inline-block">
                        <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-3">Live Transaction Chain</h2>
                        <p class="text-lg text-slate-600 dark:text-slate-400">Real-time cryptographic ledger of global market settlements.</p>
                    </div>
                    <div class="flex items-center gap-3 bg-[var(--bg-pane)] px-4 py-2 rounded-full border border-[var(--border)] shadow-sm">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wide">NETWORK LIVE</span>
                    </div>
                </div>

                <div class="chain-timeline">
                    <?php foreach ($chain_blocks as $index => $block): ?>
                        
                        <div class="chain-block glass-pane p-8 mb-10 relative">
                            <!-- Timeline Node Dot -->
                            <div class="block-node <?= ($index === count($chain_blocks)-1) ? 'genesis-node' : '' ?>"></div>
                            
                            <!-- Block Header -->
                            <div class="flex justify-between items-start mb-6 border-b border-[var(--border)] pb-6">
                                <div>
                                    <div class="flex items-center gap-4 mb-2">
                                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Block #<?= number_format($block['height']) ?></h3>
                                        <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full 
                                            <?= $block['status'] === 'Processing' ? 'bg-amber-500/10 text-amber-600 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' ?>">
                                            <?= $block['status'] ?>
                                        </span>
                                        <?php if ($block['type'] === 'AMM Genesis Contract'): ?>
                                            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-brand-glow text-brand-color border border-blue-500/20">
                                                Genesis Node
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-sm font-mono text-[var(--text-light)]">Hash: <?= $block['hash'] ?></div>
                                </div>
                                <div class="text-right text-sm font-semibold text-[var(--text-muted)]">
                                    <?= $block['timestamp'] ?>
                                </div>
                            </div>

                            <!-- Block Transactions -->
                            <div class="space-y-4">
                                <?php foreach ($block['transactions'] as $tx): ?>
                                    <div class="flex items-center p-4 rounded-xl bg-[var(--bg-subtle)] border border-[var(--border)] hover:bg-[var(--bg-stronger)] transition-colors">
                                        <div class="w-1/4">
                                            <span class="text-xs font-bold px-3 py-1 rounded-lg <?= isset($tx['color']) ? $tx['color'] : 'bg-brand-glow text-brand-color border border-blue-500/20' ?>">
                                                <?= $tx['type'] ?>
                                            </span>
                                        </div>
                                        <div class="w-1/2 text-base font-medium text-slate-800 dark:text-slate-200">
                                            <?= $tx['detail'] ?>
                                        </div>
                                        <div class="w-1/4 text-right font-mono text-xs text-[var(--text-muted)]">
                                            TXID: <?= substr($tx['txid'], 0, 10) ?>...
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>

    </main>

    <script>
        // Universal Theme Logic (Dark/Light)
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Initialize matching user system preference or saved token
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</body>
</html>