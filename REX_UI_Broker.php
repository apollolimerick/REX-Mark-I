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

$continent_icons = [
    'North America' => '🌎',
    'Europe' => '🌍',
    'Asia' => '🌏',
    'Middle East' => '🕌',
    'South America' => '🌎',
    'Oceania' => '🏄',
    'Africa' => '🌍'
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

// Deep Granularity Globe Coordinates for Cities
$city_coords = [
    'New York' => ['lat' => 40.71, 'lng' => -74.00], 'Los Angeles' => ['lat' => 34.05, 'lng' => -118.24],
    'Chicago' => ['lat' => 41.87, 'lng' => -87.62], 'Miami' => ['lat' => 25.76, 'lng' => -80.19],
    'San Francisco' => ['lat' => 37.77, 'lng' => -122.41], 'Toronto' => ['lat' => 43.65, 'lng' => -79.38],
    'Vancouver' => ['lat' => 49.28, 'lng' => -123.12], 'Montreal' => ['lat' => 45.50, 'lng' => -73.56],
    'Calgary' => ['lat' => 51.04, 'lng' => -114.07], 'Ottawa' => ['lat' => 45.42, 'lng' => -75.69],
    'Mexico City' => ['lat' => 19.43, 'lng' => -99.13], 'Guadalajara' => ['lat' => 20.65, 'lng' => -103.34],
    'Monterrey' => ['lat' => 25.68, 'lng' => -100.31], 'London' => ['lat' => 51.50, 'lng' => -0.12],
    'Manchester' => ['lat' => 53.48, 'lng' => -2.24], 'Birmingham' => ['lat' => 52.48, 'lng' => -1.89],
    'Edinburgh' => ['lat' => 55.95, 'lng' => -3.18], 'Berlin' => ['lat' => 52.52, 'lng' => 13.40],
    'Munich' => ['lat' => 48.13, 'lng' => 11.58], 'Frankfurt' => ['lat' => 50.11, 'lng' => 8.68],
    'Hamburg' => ['lat' => 53.55, 'lng' => 9.99], 'Cologne' => ['lat' => 50.93, 'lng' => 6.95],
    'Paris' => ['lat' => 48.85, 'lng' => 2.35], 'Lyon' => ['lat' => 45.76, 'lng' => 4.83],
    'Marseille' => ['lat' => 43.29, 'lng' => 5.36], 'Toulouse' => ['lat' => 43.60, 'lng' => 1.44],
    'Rome' => ['lat' => 41.90, 'lng' => 12.49], 'Milan' => ['lat' => 45.46, 'lng' => 9.19],
    'Naples' => ['lat' => 40.85, 'lng' => 14.26], 'Madrid' => ['lat' => 40.41, 'lng' => -3.70],
    'Barcelona' => ['lat' => 41.38, 'lng' => 2.16], 'Valencia' => ['lat' => 39.46, 'lng' => -0.37],
    'Seville' => ['lat' => 37.38, 'lng' => -5.98], 'Amsterdam' => ['lat' => 52.36, 'lng' => 4.90],
    'Rotterdam' => ['lat' => 51.92, 'lng' => 4.47], 'Zurich' => ['lat' => 47.37, 'lng' => 8.54],
    'Geneva' => ['lat' => 46.20, 'lng' => 6.14], 'Stockholm' => ['lat' => 59.32, 'lng' => 18.06],
    'Gothenburg' => ['lat' => 57.70, 'lng' => 11.97], 'Budapest' => ['lat' => 47.49, 'lng' => 19.04],
    'Vienna' => ['lat' => 48.20, 'lng' => 16.37], 'Warsaw' => ['lat' => 52.22, 'lng' => 21.01],
    'Krakow' => ['lat' => 50.06, 'lng' => 19.94], 'Prague' => ['lat' => 50.07, 'lng' => 14.43],
    'Dublin' => ['lat' => 53.34, 'lng' => -6.26], 'Lisbon' => ['lat' => 38.72, 'lng' => -9.13],
    'Athens' => ['lat' => 37.98, 'lng' => 23.72], 'Oslo' => ['lat' => 59.91, 'lng' => 10.75],
    'Copenhagen' => ['lat' => 55.67, 'lng' => 12.56], 'Tokyo' => ['lat' => 35.67, 'lng' => 139.65],
    'Osaka' => ['lat' => 34.69, 'lng' => 135.50], 'Kyoto' => ['lat' => 35.01, 'lng' => 135.76],
    'Yokohama' => ['lat' => 35.44, 'lng' => 139.63], 'Seoul' => ['lat' => 37.56, 'lng' => 126.97],
    'Busan' => ['lat' => 35.17, 'lng' => 129.07], 'Incheon' => ['lat' => 37.45, 'lng' => 126.70],
    'Singapore' => ['lat' => 1.28, 'lng' => 103.83], 'Dubai' => ['lat' => 25.20, 'lng' => 55.27],
    'Abu Dhabi' => ['lat' => 24.45, 'lng' => 54.37], 'Sao Paulo' => ['lat' => -23.55, 'lng' => -46.63],
    'Rio de Janeiro' => ['lat' => -22.90, 'lng' => -43.17], 'Buenos Aires' => ['lat' => -34.60, 'lng' => -58.38],
    'Santiago' => ['lat' => -33.44, 'lng' => -70.66], 'Sydney' => ['lat' => -33.86, 'lng' => 151.20],
    'Melbourne' => ['lat' => -37.81, 'lng' => 144.96], 'Brisbane' => ['lat' => -27.47, 'lng' => 153.02],
    'Perth' => ['lat' => -31.95, 'lng' => 115.86], 'Auckland' => ['lat' => -36.84, 'lng' => 174.76],
    'Johannesburg' => ['lat' => -26.20, 'lng' => 28.04], 'Cape Town' => ['lat' => -33.92, 'lng' => 18.42],
];

$geography = [
    'North America' => [
        'United States' => ['New York', 'Los Angeles', 'Chicago', 'Miami', 'San Francisco', 'Austin', 'Denver'],
        'Canada' => ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa'],
        'Mexico' => ['Mexico City', 'Guadalajara', 'Monterrey', 'Puebla', 'Tijuana'],
    ],
    'Europe' => [
        'United Kingdom' => ['London', 'Manchester', 'Birmingham', 'Edinburgh', 'Bristol', 'Glasgow'],
        'Germany' => ['Berlin', 'Munich', 'Frankfurt', 'Hamburg', 'Cologne', 'Dresden'],
        'France' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'],
        'Italy' => ['Rome', 'Milan', 'Naples', 'Turin', 'Florence'],
        'Spain' => ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao'],
        'Netherlands' => ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven'],
        'Switzerland' => ['Zurich', 'Geneva', 'Basel', 'Lausanne', 'Bern'],
        'Sweden' => ['Stockholm', 'Gothenburg', 'Malmo', 'Uppsala', 'Vasteras'],
        'Hungary' => ['Budapest', 'Debrecen', 'Szeged', 'Miskolc', 'Pecs', 'Gyor', 'Sopron'],
        'Austria' => ['Vienna', 'Graz', 'Linz', 'Salzburg', 'Innsbruck'],
    ],
    'Asia' => [
        'Japan' => ['Tokyo', 'Osaka', 'Kyoto', 'Yokohama', 'Sapporo'],
        'South Korea' => ['Seoul', 'Busan', 'Incheon', 'Daegu', 'Daejeon'],
        'Singapore' => ['Singapore'],
    ],
    'Middle East' => [
        'UAE' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Fujairah'],
    ],
    'South America' => [
        'Brazil' => ['Sao Paulo', 'Rio de Janeiro', 'Brasilia', 'Salvador', 'Fortaleza'],
        'Argentina' => ['Buenos Aires', 'Cordoba', 'Rosario', 'Mendoza', 'Tucuman'],
        'Chile' => ['Santiago', 'Valparaiso', 'Concepcion', 'La Serena', 'Antofagasta'],
    ],
    'Oceania' => [
        'Australia' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide'],
        'New Zealand' => ['Auckland', 'Wellington', 'Christchurch', 'Hamilton', 'Tauranga'],
    ],
    'Africa' => [
        'South Africa' => ['Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth'],
    ]
];

function generateDatabase() {
    global $geography, $iso_map;
    $db = [];
    
    foreach($geography as $continent => $countries) {
        $db[$continent] = [];
        
        foreach($countries as $country => $cities) {
            $db[$continent][$country] = [];
            $countryPrefix = isset($iso_map[$country]) ? $iso_map[$country] : strtoupper(substr(str_replace(' ', '', $country), 0, 3));

            foreach($cities as $city) {
                mt_srand(crc32($city));
                $cityPrefix = strtoupper(substr(str_replace(' ', '', $city), 0, 3));
                
                // 75% chance of a city being broken into regional zones, 25% chance it's just the city itself
                $has_subregions = mt_rand(0, 100) > 25; 
                
                if ($has_subregions && $city !== 'Singapore') {
                    $db[$continent][$country][$city] = [];
                    $districtPool = ['North', 'South', 'East', 'West', 'Central', 'Downtown', 'Waterfront', 'Historic', 'Financial', 'Suburbs'];
                    $numDistricts = mt_rand(3, 7);
                    
                    $shuffledTypes = $districtPool;
                    shuffle_seeded($shuffledTypes);
                    
                    for($i = 0; $i < $numDistricts; $i++) {
                        $distName = $shuffledTypes[$i];
                        $distPrefix = strtoupper(substr(str_replace(' ', '', $distName), 0, 3));
                        $ticker = $countryPrefix . '-' . $cityPrefix . '-' . $distPrefix;
                        
                        $price = mt_rand(2500, 28000) + (mt_rand(0, 99) / 100);
                        $changeVal = (mt_rand(-50, 50) / 10);
                        $changeStr = ($changeVal > 0 ? '+' : '') . number_format($changeVal, 1) . '%';
                        
                        $db[$continent][$country][$city][$distName] = [
                            'ticker' => $ticker,
                            'price' => $price,
                            'change_val' => $changeVal,
                            'change' => $changeStr
                        ];
                    }
                } else {
                    // No sub-regions. The city itself is the tradable leaf node!
                    $ticker = $countryPrefix . '-' . $cityPrefix;
                    $price = mt_rand(2500, 28000) + (mt_rand(0, 99) / 100);
                    $changeVal = (mt_rand(-50, 50) / 10);
                    $changeStr = ($changeVal > 0 ? '+' : '') . number_format($changeVal, 1) . '%';
                    
                    $db[$continent][$country][$city] = [
                        'ticker' => $ticker,
                        'price' => $price,
                        'change_val' => $changeVal,
                        'change' => $changeStr
                    ];
                }
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

// Hierarchical Aggregate Indices
function buildIndices(&$node, $level, $prefix) {
    // If node is already a leaf
    if (isset($node['ticker'])) {
        $change_val = floatval(str_replace(['+', '%'], '', $node['change']));
        $node['change_val'] = $change_val; 
        return $node;
    }

    $sum_price = 0;
    $sum_change = 0;
    $count = 0;
    
    foreach ($node as $key => &$child) {
        if ($key === '_meta') continue;
        
        global $iso_map;
        $next_prefix = '';
        if ($level === 0) { 
            $abbrev = ['North America'=>'NA', 'Europe'=>'EU', 'Asia'=>'AS', 'Middle East'=>'ME', 'South America'=>'SA', 'Oceania'=>'OC', 'Africa'=>'AF'];
            $next_prefix = isset($abbrev[$key]) ? $abbrev[$key] : strtoupper(substr(str_replace(' ', '', $key), 0, 2));
        } else if ($level === 1) { 
            $next_prefix = isset($iso_map[$key]) ? $iso_map[$key] : strtoupper(substr(str_replace(' ', '', $key), 0, 3));
        } else if ($level === 2) {
            $next_prefix = $prefix . '-' . strtoupper(substr(str_replace(' ', '', $key), 0, 3));
        } else {
            $next_prefix = $prefix;
        }
        
        $child_meta = buildIndices($child, $level + 1, $next_prefix);
        $sum_price += $child_meta['price'];
        $sum_change += (isset($child_meta['change_val']) ? $child_meta['change_val'] : 0);
        $count++;
    }
    
    $avg_price = $count > 0 ? $sum_price / $count : 0;
    $avg_change = $count > 0 ? $sum_change / $count : 0;
    
    if ($level === 0) $ticker = 'REX-GLOBAL-IDX';
    else if ($level === 1) $ticker = $prefix . '-CONT-IDX';
    else $ticker = $prefix . '-IDX';
    
    $node['_meta'] = [
        'ticker' => $ticker,
        'price' => $avg_price,
        'change_val' => $avg_change,
        'change' => ($avg_change >= 0 ? '+' : '') . number_format($avg_change, 1) . '%'
    ];
    
    return $node['_meta'];
}

$database = generateDatabase();
buildIndices($database, 0, 'REX');

// ==========================================
// 2. ROUTING LOGIC & VIEW MANAGEMENT
// ==========================================
$view = isset($_GET['view']) ? $_GET['view'] : 'terminal';

$path = isset($_GET['path']) ? explode('|', $_GET['path']) : [];
$current_level_data = $database;
$trade_target = null; 
$node_type = count($path); 
$is_leaf = false;

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

if (isset($current_level_data['ticker'])) {
    $is_leaf = true;
}

if ($view === 'terminal') {
    if ($is_leaf) {
        $trade_target = $current_level_data;
    } elseif (isset($current_level_data['_meta'])) {
        $trade_target = $current_level_data['_meta'];
    }
}

function buildPath($currentPath, $newNode) {
    return '?view=terminal&path=' . urlencode(implode('|', array_merge($currentPath, [$newNode])));
}

function getIcon($keyName, $nodeType) {
    global $flags, $continent_icons;
    if ($nodeType === 0) return isset($continent_icons[$keyName]) ? $continent_icons[$keyName] . ' ' : '';
    if ($nodeType === 1) return isset($flags[$keyName]) ? $flags[$keyName] . ' ' : '';
    return '';
}

function getFlag($countryName) {
    global $flags;
    return isset($flags[$countryName]) ? $flags[$countryName] . ' ' : '';
}

// Globe Markers
$globe_markers = [];
foreach ($database as $cont => $countries) {
    if ($cont === '_meta') continue;
    foreach ($countries as $country => $cities) {
        if ($country === '_meta') continue;
        foreach ($cities as $city => $data) {
            if ($city === '_meta') continue;
            if (isset($city_coords[$city])) {
                $meta = isset($data['ticker']) ? $data : $data['_meta'];
                $globe_markers[] = [
                    'lat' => $city_coords[$city]['lat'],
                    'lng' => $city_coords[$city]['lng'],
                    'city' => $city,
                    'ticker' => $meta['ticker'],
                    'change' => $meta['change'],
                    'val' => $meta['change_val'],
                    'price' => $meta['price'],
                    'path' => urlencode($cont.'|'.$country.'|'.$city)
                ];
            }
        }
    }
}

function getSentiment($change_val) {
    $score = 50 + ($change_val * 15);
    $score = max(10, min(98, $score)); 
    $label = $score >= 50 ? 'BULLISH' : 'BEARISH';
    $color = $score >= 50 ? 'text-emerald-500' : 'text-rose-500';
    $bg = $score >= 50 ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-rose-500/10 border-rose-500/20';
    return ['score' => round($score), 'label' => $label, 'color' => $color, 'bg' => $bg];
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
    $num_houses = mt_rand(40, 80); // Increased rows
    
    for($i = 0; $i < $num_houses; $i++) {
        $avm_val = $avg_home_val * (mt_rand(60, 150) / 100); 
        $tokenized_pct = mt_rand(5, 25); 
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
    
    $avg_tok = mt_rand(12, 250) / 10; 
    $short_int = mt_rand(20, 150) / 10; 
    $vol = mt_rand(1, 15) . "." . mt_rand(1, 9) . "M";
    
    $district_houses = [];
    $active_nodes = 0;
    if ($is_leaf) {
        $district_houses = generateProperties($trade_target['ticker'], $trade_target['price']);
        $active_nodes = count($district_houses);
    } else {
        $active_nodes = mt_rand(500, 15000); 
    }

    $chartData = [];
    $current_c = $trade_target['price']; 
    for ($d = 0; $d <= 1825; $d++) {
        $date = date('Y-m-d', strtotime("-$d days"));
        $volatility = $current_c * 0.025; 
        $o = $current_c + mt_rand(-$volatility, $volatility);
        $h = max($o, $current_c) + mt_rand(0, $volatility/2);
        $l = min($o, $current_c) - mt_rand(0, $volatility/2);
        
        array_unshift($chartData, [
            'd' => $date, 
            'o' => round($o,2), 'h' => round($h,2), 'l' => round($l,2), 'c' => round($current_c,2)
        ]);
        $current_c = $o; 
    }
}

// ==========================================
// 4. PORTFOLIO DATA GENERATOR
// ==========================================
if ($view === 'portfolio') {
    $flat_tickers_port = [];
    foreach($database as $cont => $countries) {
        if ($cont === '_meta') continue;
        foreach($countries as $country => $cities) {
            if ($country === '_meta') continue;
            foreach($cities as $city => $districts) {
                if ($city === '_meta') continue;
                if (isset($districts['ticker'])) {
                    $flat_tickers_port[] = $districts['ticker'];
                } else {
                    foreach($districts as $dist => $data) {
                        if ($dist === '_meta') continue;
                        $flat_tickers_port[] = $data['ticker'];
                    }
                }
            }
        }
    }
    mt_srand(101010); 
    shuffle($flat_tickers_port);
    
    $port_chart_data = [];
    $current_port_val = 185000;
    for ($d = 0; $d <= 1825; $d++) {
        $date = date('Y-m-d', strtotime("-$d days"));
        array_unshift($port_chart_data, ['d' => $date, 'c' => $current_port_val]);
        $current_port_val = $current_port_val - mt_rand(-1500, 1600); 
    }
    
    $current_portfolio_value = $port_chart_data[1825]['c']; 
    $total_invested = 145000;
    $total_pl_pct = (($current_portfolio_value - $total_invested) / $total_invested) * 100;
    $total_pl_abs = $current_portfolio_value - $total_invested;
    $cash_balance = 24500.50;

    // Added more rows to portfolio
    $portfolio_holdings = [
        ['ticker' => $flat_tickers_port[0], 'shares' => 15.5, 'avg_price' => 12500, 'current_price' => 13200, 'status' => 'Liquid', 'days_left' => 0],
        ['ticker' => $flat_tickers_port[1], 'shares' => 42.0, 'avg_price' => 5400, 'current_price' => 4900, 'status' => 'Locked', 'days_left' => 12],
        ['ticker' => $flat_tickers_port[2], 'shares' => 8.25, 'avg_price' => 22000, 'current_price' => 24500, 'status' => 'Liquid', 'days_left' => 0],
        ['ticker' => $flat_tickers_port[3], 'shares' => 110.0, 'avg_price' => 1100, 'current_price' => 1250, 'status' => 'Locked', 'days_left' => 350],
        ['ticker' => $flat_tickers_port[4], 'shares' => 5.5, 'avg_price' => 8000, 'current_price' => 8400, 'status' => 'Liquid', 'days_left' => 0],
        ['ticker' => $flat_tickers_port[5], 'shares' => 20.0, 'avg_price' => 3200, 'current_price' => 2900, 'status' => 'Locked', 'days_left' => 45],
    ];

    $portfolio_trades = [
        ['date' => date('M d, Y', strtotime('-1 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[0], 'shares' => 5.0, 'price' => 13100],
        ['date' => date('M d, Y', strtotime('-3 days')), 'type' => 'SELL', 'ticker' => $flat_tickers_port[6], 'shares' => 12.0, 'price' => 8200],
        ['date' => date('M d, Y', strtotime('-5 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[2], 'shares' => 8.25, 'price' => 22000],
        ['date' => date('M d, Y', strtotime('-12 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[1], 'shares' => 42.0, 'price' => 5400],
        ['date' => date('M d, Y', strtotime('-15 days')), 'type' => 'VAULT LOCK', 'ticker' => $flat_tickers_port[3], 'shares' => 110.0, 'price' => 1100],
        ['date' => date('M d, Y', strtotime('-18 days')), 'type' => 'BUY', 'ticker' => $flat_tickers_port[4], 'shares' => 5.5, 'price' => 8000],
        ['date' => date('M d, Y', strtotime('-22 days')), 'type' => 'VAULT LOCK', 'ticker' => $flat_tickers_port[5], 'shares' => 20.0, 'price' => 3200],
    ];
}

// Generate Mock Blockchain Data if View is 'chain'
$chain_blocks = [];
$all_tickers = ['REX-GLOBAL-IDX']; 
$filter_ticker = isset($_GET['filter_ticker']) && $_GET['filter_ticker'] !== 'ALL' ? $_GET['filter_ticker'] : 'ALL';
$filter_type = isset($_GET['filter_type']) && $_GET['filter_type'] !== 'ALL' ? $_GET['filter_type'] : 'ALL';

if ($view === 'chain') {
    foreach($database as $cont => $countries) {
        if ($cont === '_meta') continue;
        if (isset($countries['_meta'])) $all_tickers[] = $countries['_meta']['ticker'];
        foreach($countries as $country => $cities) {
            if ($country === '_meta') continue;
            if (isset($cities['_meta'])) $all_tickers[] = $cities['_meta']['ticker'];
            foreach($cities as $city => $districts) {
                if ($city === '_meta') continue;
                if (isset($districts['ticker'])) {
                    $all_tickers[] = $districts['ticker'];
                } else {
                    if (isset($districts['_meta'])) $all_tickers[] = $districts['_meta']['ticker'];
                    foreach($districts as $dist => $data) {
                        if ($dist === '_meta') continue;
                        if (isset($data['ticker'])) $all_tickers[] = $data['ticker'];
                    }
                }
            }
        }
    }
    sort($all_tickers);

    mt_srand(time()); 
    $sample_tickers = ($filter_ticker !== 'ALL') ? [$filter_ticker] : array_slice($all_tickers, mt_rand(0, count($all_tickers) - 15), 15);

    $genesis_desc = ($filter_ticker !== 'ALL') ? "Initial AMM Pool for $filter_ticker" : 'Initial Global AMM Pool: $100,000,000 USD / 10,000,000 REX-SHARES';
    $chain_blocks[] = [
        'height' => 0,
        'hash' => '0x0000000000000000000000000000000000000000',
        'timestamp' => date('M d Y, H:i:s', strtotime('-30 days')),
        'type' => 'AMM Genesis Contract',
        'status' => 'Confirmed',
        'transactions' => [
            [
                'txid' => '0x'.substr(md5('genesis_tx'.$filter_ticker), 0, 16), 
                'type' => 'LIQUIDITY_ADD', 
                'color' => 'text-blue-500 bg-blue-500/10 border-blue-500/20',
                'detail' => $genesis_desc
            ]
        ]
    ];

    $tx_types = ['BUY', 'SELL', 'SHORT', 'VAULT_LOCK', 'TOKENIZE'];
    $current_height = mt_rand(450000, 480000);

    for ($b = 1; $b <= 15; $b++) { // Increased rows
        $txs = [];
        $num_tx = mt_rand(2, 5); // Increased rows
        
        for ($t = 0; $t < $num_tx; $t++) {
            $t_type = ($filter_type !== 'ALL') ? $filter_type : $tx_types[array_rand($tx_types)];
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
            } elseif ($t_type == 'VAULT_LOCK') {
                $detail = "Locked ".number_format($amount/100, 2)." shares of ".$t_tick."";
                $color = "text-purple-500 bg-purple-500/10 border-purple-500/20";
            } else {
                $detail = "Underwrote Property Hash 0x".strtoupper(substr(md5(time().$t), 0, 6))." into ".number_format($amount/10, 2)." shares of ".$t_tick;
                $color = "text-cyan-500 bg-cyan-500/10 border-cyan-500/20";
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
            'timestamp' => date('M d Y, H:i:s', strtotime('-'.(15-$b).' minutes')),
            'type' => 'Standard Settlement',
            'status' => ($b === 15) ? 'Processing' : 'Confirmed',
            'transactions' => $txs
        ];
    }
    $chain_blocks = array_reverse($chain_blocks);
}
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REX | Global Asset Terminal</title>
    <!-- Injected Tailwind, Chart.js, Globe.gl & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/globe.gl"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
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
                        mono: ['JetBrains Mono', 'monospace'],
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
            
            --bg-body: rgba(248, 250, 252, 0.5); 
            --bg-pane: rgba(255, 255, 255, 0.65); 
            --bg-pane-hover: rgba(255, 255, 255, 0.8); 
            --bg-subtle: rgba(255, 255, 255, 0.4);
            --bg-stronger: rgba(0, 0, 0, 0.04);
            
            --text-main: #0f172a; 
            --text-muted: #475569; 
            --text-light: #94a3b8; 
            
            --accent-up: #10b981; 
            --accent-down: #f43f5e; 
            
            --border: rgba(255, 255, 255, 0.4); 
            --border-highlight: rgba(255, 255, 255, 1); 
            
            --shadow-sm: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 16px 48px rgba(0, 0, 0, 0.12);
            
            --radius-card: 24px; 
            --blur-amount: 24px; 
        }

        .dark {
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
        
        .bg-canvas { position: fixed; top: -20vh; left: -20vw; width: 140vw; height: 140vh; z-index: -2; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #020617; }
        .netflix-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 20px; transform: rotate(-18deg) scale(1.6); width: 160vw; filter: blur(4px); opacity: 0.5; transition: opacity 0.5s, filter 0.5s; }
        .dark .netflix-grid { opacity: 0.2; filter: blur(6px); }
        .netflix-img { width: 100%; aspect-ratio: 16 / 9; background-size: cover; background-position: center; border-radius: 8px; background-color: #334155; box-shadow: 0 4px 15px rgba(0,0,0,0.8); }
        .light-halo { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 120vw; height: 120vh; background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 60%); z-index: -1; pointer-events: none; }
        .dark .light-halo { background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, rgba(15, 23, 42, 0) 70%); }

        .glass-pane {
            background: var(--bg-pane); border: 1px solid var(--border);
            border-top: 1px solid var(--border-highlight); border-left: 1px solid var(--border-highlight);
            box-shadow: var(--shadow-sm); backdrop-filter: blur(var(--blur-amount));
            -webkit-backdrop-filter: blur(var(--blur-amount)); border-radius: var(--radius-card);
            overflow: hidden; position: relative;
        }

        /* Nav Gradient Pills Restored */
        .header-nav { display: inline-flex; align-items: center; gap: 4px; background: var(--bg-subtle); padding: 4px; border-radius: 14px; border: 1px solid var(--border); box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); white-space: nowrap; }
        .nav-btn { padding: 8px 16px; border-radius: 10px; font-size: 14px; font-weight: 700; color: var(--text-muted); text-decoration: none; transition: all 0.3s; border: 1px solid transparent; background: transparent; white-space: nowrap; }
        .nav-btn:hover { color: var(--text-main); background: var(--bg-pane); border-color: var(--border); }
        
        .nav-btn.active-terminal { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff !important; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); border-color: #60a5fa; }
        .nav-btn.active-offer { background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #ffffff !important; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4); border-color: #a78bfa; }
        .nav-btn.active-portfolio { background: linear-gradient(135deg, #059669, #047857); color: #ffffff !important; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4); border-color: #34d399; }
        .nav-btn.active-chain { background: linear-gradient(135deg, #d97706, #b45309); color: #ffffff !important; box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4); border-color: #fbbf24; }
        .nav-btn.active-advanced { background: linear-gradient(135deg, #db2777, #9d174d); color: #ffffff !important; box-shadow: 0 4px 15px rgba(219, 39, 119, 0.4); border-color: #f472b6; }
        .nav-btn.active-about { background: linear-gradient(135deg, #0f172a, #334155); color: #ffffff !important; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.4); border-color: #475569; }

        .hide-scrollbar::-webkit-scrollbar { display: none; }

        /* Make Grid Denser to fix "less rows" */
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
        
        .dashboard-header { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 40px; }
        .metric-panel { padding: 40px; }
        .ticker-display { font-size: 15px; color: var(--text-muted); margin-bottom: 16px; font-weight: 600; display: flex; align-items: center; gap: 12px;}
        .ticker-badge { background: var(--brand-glow); padding: 6px 14px; border-radius: 999px; color: var(--brand-color); font-weight: 700; border: 1px solid rgba(59,130,246,0.3);}
        .price-display { font-size: 56px; font-weight: 800; letter-spacing: -2px; margin-bottom: 12px; color: var(--text-main); line-height: 1;}
        .change-up { color: var(--accent-up); font-weight: 600; background: rgba(16, 185, 129, 0.1); padding: 8px 16px; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px; font-size: 15px;}
        .change-down { color: var(--accent-down); font-weight: 600; background: rgba(244, 63, 94, 0.1); padding: 8px 16px; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px; font-size: 15px;}
        .mini-stats { display: flex; gap: 56px; margin-top: 40px; padding-top: 40px; border-top: 1px solid var(--border); }
        .mini-stat-box { display: flex; flex-direction: column; gap: 10px; }
        .mini-stat-label { font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;}
        .mini-stat-val { font-size: 22px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;}
        .heat-bar-container { margin-top: 20px; background: var(--bg-stronger); height: 10px; border-radius: 999px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .heat-bar-fill { height: 100%; background: linear-gradient(90deg, var(--brand-color), #60a5fa); border-radius: 999px; transition: width 1.2s; }

        .data-panel-header { padding: 32px 40px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-subtle);}
        .data-panel-title { font-size: 20px; font-weight: 700; color: var(--text-main); letter-spacing: -0.4px;}
        .breadcrumb-link { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        .breadcrumb-link:hover { color: var(--brand-color); }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 20px 32px; background: var(--bg-stronger); font-size: 13px; font-weight: 800; color: var(--text-muted); border-bottom: 1px solid var(--border); text-transform: uppercase; letter-spacing: 1px;}
        .dark th { color: #94a3b8; }
        td { padding: 24px 32px; font-size: 15px; border-bottom: 1px solid var(--border); color: var(--text-main); font-weight: 600; transition: background 0.2s;}
        .dark td { color: #f8fafc; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-subtle); }
        .hash-col { color: var(--text-light); font-weight: 400; font-family: 'JetBrains Mono', monospace; font-size: 14px;}
        .tag { background: var(--bg-stronger); border: 1px solid var(--border); padding: 8px 14px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--text-muted);}
        .shares-badge { color: var(--brand-color); font-weight: 700; font-size: 16px;}

        .btn-trade { background: var(--brand-color); color: white; border: none; padding: 12px 28px; border-radius: 999px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px var(--brand-glow); }
        .btn-trade:hover { background: var(--brand-hover); transform: translateY(-2px); box-shadow: 0 8px 20px var(--brand-glow);}

        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index: 999; opacity: 0; pointer-events: none; transition: opacity 0.4s ease; }
        .overlay.active { opacity: 1; pointer-events: auto; }

        .side-panel { position: fixed; top: 0; right: -520px; width: 520px; height: 100vh; background: var(--bg-pane); z-index: 1000; box-shadow: -10px 0 40px rgba(0,0,0,0.2); transition: right 0.4s; display: flex; flex-direction: column; border-left: 1px solid var(--border-highlight); backdrop-filter: blur(32px); -webkit-backdrop-filter: blur(32px); }
        .side-panel.active { right: 0; }
        .panel-header { padding: 40px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-subtle);}
        .panel-title { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px;}
        .panel-title-badge { font-size: 14px; background: var(--brand-glow); color: var(--brand-color); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3);}
        .btn-close { background: var(--bg-stronger); color: var(--text-main); border: 1px solid var(--border); width: 40px; height: 40px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .btn-close:hover { background: var(--bg-subtle); transform: rotate(90deg); }

        .trade-tabs { display: flex; border-bottom: 1px solid var(--border); padding: 0 40px;}
        .trade-tab { padding: 24px 0; margin-right: 40px; color: var(--text-muted); font-weight: 600; font-size: 16px; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .trade-tab:hover { color: var(--text-main); }
        .trade-tab.active { color: var(--brand-color); border-bottom-color: var(--brand-color); }
        .tab-content { padding: 40px; display: none; flex: 1; overflow-y: auto;}
        .tab-content.active { display: block; }
        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 15px; color: var(--text-muted); margin-bottom: 12px; font-weight: 600;}
        .form-input-wrapper { position: relative; display: flex; align-items: center; }
        .form-input-wrapper span { position: absolute; left: 20px; color: var(--text-muted); font-size: 20px; font-weight: 700;}
        .form-group input, .form-group select { width: 100%; background: var(--bg-subtle); border: 1px solid var(--border); color: var(--text-main); font-size: 24px; font-weight: 700; padding: 16px 20px 16px 48px; border-radius: 16px; outline: none; transition: all 0.2s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
        .form-group select { padding-left: 20px; font-size: 18px; appearance: none; }
        .form-group input:focus, .form-group select:focus { border-color: var(--brand-color); background: var(--bg-pane); box-shadow: 0 0 0 4px var(--brand-glow), inset 0 2px 4px rgba(0,0,0,0.05); }

        .btn-submit { width: 100%; background: var(--brand-color); color: white; border: none; padding: 20px; border-radius: 16px; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 24px var(--brand-glow); }
        .btn-submit:hover { background: var(--brand-hover); transform: translateY(-2px); }

        .chart-panel { position: fixed; top: 104px; left: -100vw; width: calc(100vw - 568px); height: calc(100vh - 128px); background: var(--bg-pane); z-index: 1001; box-shadow: var(--shadow-hover); transition: left 0.4s; border: 1px solid var(--border); border-top: 1px solid var(--border-highlight); border-left: 1px solid var(--border-highlight); border-radius: var(--radius-card); backdrop-filter: blur(var(--blur-amount)); -webkit-backdrop-filter: blur(var(--blur-amount)); padding: 36px 48px; display: flex; flex-direction: column; }
        .chart-panel.active { left: 24px; }
        @media (max-width: 1200px) { .chart-panel { display: none !important; } }
        
        .time-toggle { padding: 6px 12px; font-size: 13px; font-weight: 700; border-radius: 8px; color: var(--text-muted); cursor: pointer; transition: all 0.2s; border: none; background: transparent; }
        .time-toggle:hover { background: var(--bg-stronger); color: var(--text-main); }
        .time-toggle.active { background: var(--brand-color); color: white; box-shadow: 0 2px 8px var(--brand-glow); }

        .chain-timeline { position: relative; padding-left: 48px; }
        .chain-timeline::before { content: ''; position: absolute; left: 16px; top: 0; bottom: 0; width: 4px; background: var(--border); border-radius: 4px; }
        .block-node { position: absolute; left: -44px; top: 32px; width: 24px; height: 24px; border-radius: 50%; background: var(--brand-color); border: 4px solid var(--bg-pane); box-shadow: 0 0 0 4px var(--brand-glow); z-index: 10; }
        .genesis-node { background: #059669; box-shadow: 0 0 0 4px rgba(5,150,105,0.2); }
        .chain-block { transition: transform 0.2s; }
        .chain-block:hover { transform: translateX(8px); }

        .copy-btn { cursor: pointer; transition: color 0.2s; display: inline-flex; align-items: center; gap: 4px; }
        .copy-btn:hover { color: var(--brand-color); }
        .copy-btn svg { width: 14px; height: 14px; opacity: 0.5; transition: opacity 0.2s; }
        .copy-btn:hover svg { opacity: 1; }

        .toast-notification { position: fixed; bottom: 40px; right: 40px; background: var(--brand-color); color: white; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 40px rgba(37, 99, 235, 0.4); opacity: 0; transform: translateY(20px); transition: all 0.3s; z-index: 9999; pointer-events: none; display: flex; align-items: center; gap: 10px; }
        .toast-notification.show { opacity: 1; transform: translateY(0); }
        
        /* Auth Modal Specifics */
        .auth-modal { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); opacity: 0; pointer-events: none; transition: opacity 0.3s; }
        .auth-modal.active { opacity: 1; pointer-events: auto; }
        .auth-box { transform: scale(0.95); transition: transform 0.3s; }
        .auth-modal.active .auth-box { transform: scale(1); }

    </style>
</head>
<body class="text-slate-800 dark:text-slate-300 antialiased min-h-screen relative font-sans">

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

    <!-- Header with Navigation Pills & Auth Buttons -->
    <header class="fixed top-0 w-full z-50 px-6 py-4 flex flex-col md:flex-row justify-between items-center bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-white/40 dark:border-slate-700/50 transition-colors shadow-sm gap-4 md:gap-6">
        
        <!-- Left Side: REX Logo -->
        <a href="?view=terminal" class="flex items-center gap-3 flex-shrink-0 no-underline cursor-pointer transition-transform hover:scale-[1.02]">
            <img src="rex_logo.png" alt="REX Logo" class="h-9 w-auto drop-shadow-sm" onerror="this.style.display='none'">
            <div class="flex items-center font-serif text-lg tracking-wide text-slate-900 dark:text-white drop-shadow-sm whitespace-nowrap">
                <span class="font-black text-xl tracking-[0.1em] bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-500 dark:from-slate-100 dark:to-slate-400">REX&reg; - </span> 
                <span class="hidden lg:inline font-semibold opacity-90 ml-2">Residential Property Exchange</span>
            </div>
        </a>
        
        <!-- Center Navigation Pane -->
        <div class="flex-1 flex justify-center w-full md:w-auto overflow-x-auto hide-scrollbar pb-1 md:pb-0">
            <nav class="header-nav">
                <a href="?view=terminal" class="nav-btn <?= $view === 'terminal' ? 'active-terminal' : '' ?>">Global Asset Terminal</a>
                <a href="?view=offer" class="nav-btn <?= $view === 'offer' ? 'active-offer' : '' ?>">Offer Home Equity</a>
                <a href="?view=portfolio" class="nav-btn <?= $view === 'portfolio' ? 'active-portfolio' : '' ?>">My Portfolio</a>
                <a href="?view=chain" class="nav-btn <?= $view === 'chain' ? 'active-chain' : '' ?>">View Transaction Chain</a>
                <a href="?view=advanced" class="nav-btn <?= $view === 'advanced' ? 'active-advanced' : '' ?>">Advanced Data</a>
                <div class="h-6 w-px bg-[var(--border)] mx-1 hidden sm:block"></div>
                <a href="?view=about" class="nav-btn <?= $view === 'about' ? 'active-about' : '' ?>">About REX</a>
            </nav>
        </div>

        <!-- Right Side: Auth & Theme Toggle -->
        <div class="flex items-center gap-4 flex-shrink-0">
            <button onclick="toggleDarkMode()" class="p-2 rounded-full bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-700/80 transition-colors border border-white/50 dark:border-slate-600/50 shadow-sm" title="Toggle Theme">
                <svg id="icon-sun" class="w-5 h-5 hidden dark:block text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="icon-moon" class="w-5 h-5 block dark:hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            <div class="h-5 w-px bg-[var(--border)] mx-1 hidden sm:block"></div>
            <button onclick="openAuthModal('login')" class="hidden sm:block text-sm font-bold text-[var(--text-muted)] hover:text-[var(--text-main)] transition-colors tracking-wide">Log In</button>
            <button onclick="openAuthModal('signup')" class="hidden sm:block bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-4 py-2 rounded-xl text-sm font-bold shadow-md hover:-translate-y-0.5 transition-transform tracking-wide">Sign Up</button>
        </div>
    </header>

    <!-- Global Auth Modal -->
    <div id="authModal" class="auth-modal">
        <div class="auth-box bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700 rounded-2xl p-8 w-full max-w-md shadow-2xl relative mx-4">
            <button onclick="closeAuthModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6" id="authTitle">Secure Access</h2>
            <div class="form-group mb-4">
                <input type="text" id="authUsername" placeholder="Email or Username" class="w-full !p-4 !text-lg !font-medium bg-slate-50 dark:bg-slate-800/50">
            </div>
            <div class="form-group mb-6">
                <input type="password" id="authPassword" placeholder="Password" class="w-full !p-4 !text-lg !font-medium bg-slate-50 dark:bg-slate-800/50">
            </div>
            <button class="w-full bg-[#2563eb] text-white font-bold py-4 rounded-xl hover:bg-[#1d4ed8] transition shadow-md" onclick="alert('Simulation: Authentication Successful'); closeAuthModal()">
                Continue
            </button>
        </div>
    </div>

    <main class="pt-36 pb-24 px-6 sm:px-8 lg:px-12 max-w-[1500px] mx-auto relative z-10">
        
        <?php if ($view === 'terminal'): ?>
            <!-- Premium Glass Breadcrumbs & Back Button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-4">
                <div class="flex items-center gap-4 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0 hide-scrollbar">
                    <?php if ($node_type > 0): ?>
                        <a href="<?= $back_url ?>" class="glass-pane px-5 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-brand-color dark:hover:text-brand-color transition-colors inline-flex items-center gap-2 border-r-2 flex-shrink-0" style="border-radius: 12px;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back
                        </a>
                    <?php endif; ?>
                    
                    <div class="glass-pane px-8 py-3 text-base font-semibold inline-flex items-center flex-wrap flex-shrink-0" style="border-radius: 12px;">
                        <a href="?view=terminal" class="breadcrumb-link">Global Markets</a>
                        <?php
                            $build_path = [];
                            foreach ($path as $index => $node) {
                                $build_path[] = $node;
                                $displayName = $node;
                                if ($index === 1) $displayName = getFlag($node) . $displayName;
                                echo '<span class="mx-4 opacity-40" style="color: var(--text-muted)">/</span> <a href="?view=terminal&path=' . urlencode(implode('|', $build_path)) . '" class="breadcrumb-link">' . htmlspecialchars($displayName) . '</a>';
                            }
                        ?>
                    </div>
                </div>

                <?php if ($node_type === 0): ?>
                    <!-- Globe Toggle Button (Only on Global View) -->
                    <button onclick="toggleGlobe()" class="text-sm font-bold bg-blue-500/10 text-blue-500 border border-blue-500/20 px-5 py-3 rounded-xl hover:bg-blue-500/20 transition-colors flex items-center gap-2 shadow-sm flex-shrink-0">
                        🌍 Select on Map
                    </button>
                <?php endif; ?>
            </div>

            <!-- Globe Container (Hidden by default) -->
            <?php if ($node_type === 0): ?>
                <div id="globe-container" class="hidden w-full h-[650px] mb-12 rounded-3xl overflow-hidden border border-slate-700 shadow-2xl relative bg-[#020617] flex items-center justify-center">
                    <!-- High Contrast Base Map is loaded via JS -->
                </div>
            <?php endif; ?>

            <?php if ($is_leaf && $trade_target): ?>
                
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
                    <div class="overflow-x-auto">
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
                                    <td class="hash-col copy-btn" onclick="copyToClipboard('<?= $home['hash'] ?>')">
                                        <?= $home['hash'] ?>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </td>
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
                </div>

            <?php else: ?>

                <div class="mb-12 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="glass-pane px-10 py-6 inline-block w-full lg:w-auto">
                        <div style="font-size: 36px; font-weight: 800; color: var(--text-main); font-family: 'Playfair Display', serif; letter-spacing: -0.5px;">
                            <?php 
                                if($node_type === 0) echo "Explore Global Markets";
                                elseif($node_type === 1) echo getIcon(end($path), 0) . " Explore " . end($path);
                                elseif($node_type === 2) echo getIcon(end($path), 1) . " Cities in " . end($path);
                                elseif($node_type === 3) echo "Regions in " . end($path);
                            ?>
                        </div>
                    </div>
                    
                    <?php if (isset($current_level_data['_meta'])): ?>
                        <?php 
                            $meta = $current_level_data['_meta']; 
                            $is_up = $meta['change_val'] >= 0;
                            $c_color = $is_up ? 'text-emerald-500 bg-emerald-500/10 border border-emerald-500/20' : 'text-rose-500 bg-rose-500/10 border border-rose-500/20';
                        ?>
                        <div class="glass-pane px-8 py-5 flex items-center justify-between gap-8 w-full lg:w-auto">
                            <div class="text-left lg:text-right">
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Aggregate Index • <?= $meta['ticker'] ?></div>
                                <div class="text-3xl font-black text-slate-900 dark:text-white font-mono flex items-center gap-4">
                                    $<?= number_format($meta['price'], 2) ?>
                                    <span class="text-base px-3 py-1 rounded-full <?= $c_color ?>"><?= $meta['change'] ?></span>
                                </div>
                            </div>
                            <button class="btn-trade shadow-lg whitespace-nowrap" onclick="openPanel()">Trade <?= $meta['ticker'] ?></button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="grid-menu">
                    <?php foreach ($current_level_data as $key => $value): ?>
                        <?php if ($key === '_meta') continue; ?>
                        
                        <!-- Restyled Menu Card matching exact typography from reference images -->
                        <a href="<?= buildPath($path, $key) ?>" class="glass-pane group min-h-[160px] p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform">
                            <div>
                                <h3 class="text-2xl font-bold mb-1 text-slate-900 dark:text-white tracking-tight">
                                    <?= getIcon($key, $node_type) ?><?= htmlspecialchars($key) ?>
                                </h3>
                                
                                <?php 
                                    // Unified Display Logic for BOTH Regions and Direct Markets
                                    $is_region = isset($value['_meta']);
                                    $meta = $is_region ? $value['_meta'] : $value;
                                    $is_up = $meta['change_val'] >= 0;
                                    $c_color = $is_up ? 'text-emerald-500' : 'text-rose-500';
                                    $child_count = $is_region ? count($value) - 1 : 0; 
                                    $label = $is_region ? (($node_type === 0) ? 'Countries' : (($node_type === 1) ? 'Cities' : 'Regions')) : 'DIRECT MARKET';
                                ?>
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.15em] mb-4">
                                    <?= $is_region ? $child_count . ' ' . $label : $label ?>
                                </div>
                                <div class="text-slate-500 dark:text-slate-400 font-mono text-[13px] font-bold mb-1">
                                    <?= $meta['ticker'] ?>
                                </div>
                                <div class="text-slate-900 dark:text-white font-black text-2xl font-mono flex items-baseline gap-2">
                                    $<?= number_format($meta['price'], 2) ?>
                                    <span class="text-sm font-bold <?= $c_color ?>"><?= $meta['change'] ?></span>
                                </div>
                            </div>
                            <div class="text-blue-600 dark:text-[#3b82f6] font-semibold text-sm mt-5 flex items-center gap-1 group-hover:text-blue-500 transition-colors">
                                View Markets &rarr;
                            </div>
                        </a>
                        
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <!-- Trade Overlay & Side Panel (Global for any Terminal View) -->
            <?php if ($trade_target): ?>
                <div class="overlay" id="overlay" onclick="closeTradeWindows()"></div>
                
                <div class="side-panel" id="tradePanel">
                    <div class="panel-header">
                        <div class="panel-title">Trade <span class="panel-title-badge"><?= $trade_target['ticker'] ?></span></div>
                        <button class="btn-close" onclick="closeTradeWindows()">×</button>
                    </div>
                    
                    <div class="trade-tabs">
                        <div class="trade-tab active" onclick="switchTab('buy')">Buy</div>
                        <div class="trade-tab" onclick="switchTab('sell')">Sell</div>
                        <div class="trade-tab" onclick="switchTab('short')">Short</div>
                        <div class="trade-tab" onclick="switchTab('vault')">Vault</div>
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
                        <div class="form-group" style="margin-bottom: 32px;">
                            <label>Receive Shares</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-buy-shares" placeholder="0.00" oninput="syncInputs('buy', 'shares')">
                            </div>
                        </div>
                        <!-- Execution Details -->
                        <div class="flex justify-between items-center text-xs font-semibold text-[var(--text-muted)] mb-6 px-2">
                            <span>Routing: <span class="text-[var(--brand-color)]">REX AMM</span></span>
                            <span>Est. Fee: <span class="text-slate-900 dark:text-white" id="fee-buy">$0.00 (0.1%)</span></span>
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
                        <div class="form-group" style="margin-bottom: 32px;">
                            <label>Sell Shares</label>
                            <div class="form-input-wrapper">
                                <span style="font-size: 16px;">▤</span>
                                <input type="number" id="input-sell-shares" placeholder="0.00" oninput="syncInputs('sell', 'shares')">
                            </div>
                        </div>
                        <!-- Execution Details -->
                        <div class="flex justify-between items-center text-xs font-semibold text-[var(--text-muted)] mb-6 px-2">
                            <span>Routing: <span class="text-[var(--brand-color)]">REX AMM</span></span>
                            <span>Est. Fee: <span class="text-slate-900 dark:text-white" id="fee-sell">$0.00 (0.1%)</span></span>
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
                        <div class="p-5 rounded-xl border border-rose-500/30 bg-rose-500/5 mb-6 text-sm">
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
                        
                        <!-- Execution Details -->
                        <div class="flex justify-between items-center text-xs font-semibold text-[var(--text-muted)] mb-4 px-2">
                            <span>Routing: <span class="text-[var(--brand-color)]">REX Margin Pool</span></span>
                            <span>Borrow APY: <span class="text-slate-900 dark:text-white">8.5%</span></span>
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
                            <select class="w-full bg-[var(--bg-subtle)] border border-[var(--border)] text-[var(--text-main)] font-semibold p-4 rounded-xl outline-none focus:border-[var(--brand-color)] transition-all cursor-pointer">
                                <option value="1w">1 Week Lockup (2.0% APY)</option>
                                <option value="1m">1 Month Lockup (2.5% APY)</option>
                                <option value="6m">6 Month Lockup (6.0% APY)</option>
                                <option value="1y">1 Year Lockup (8.5% APY)</option>
                            </select>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-3 font-medium">
                                <span class="text-rose-500 font-bold">*</span> Early withdrawal incurs a 2% penalty fee.
                            </p>
                        </div>

                        <button class="btn-submit" style="background: var(--brand-color);" onclick="alert('Simulation: Shares locked in REX Vault.')">Lock Shares</button>
                    </div>
                </div>

                <!-- Dynamic Chart Window Elevated Above Overlay -->
                <div class="chart-panel" id="chartPanel">
                    <button class="btn-close" style="position: absolute; top: 24px; right: 24px;" onclick="closeTradeWindows()">×</button>
                    
                    <div class="flex justify-between items-start mb-6 pr-12">
                        <div>
                            <h3 class="font-bold text-3xl text-slate-900 dark:text-white tracking-tight mb-1"><?= $trade_target['ticker'] ?></h3>
                            <p class="text-sm font-semibold" style="color: var(--text-muted)">Live Market Trajectory</p>
                        </div>
                        
                        <div class="flex flex-col items-end gap-3">
                            <div class="flex bg-[var(--bg-stronger)] border border-[var(--border)] rounded-xl p-1">
                                <button class="time-toggle t-btn" id="t-btn-7" onclick="updateTerminalChart(7)">1W</button>
                                <button class="time-toggle t-btn active" id="t-btn-30" onclick="updateTerminalChart(30)">1M</button>
                                <button class="time-toggle t-btn" id="t-btn-365" onclick="updateTerminalChart(365)">1Y</button>
                                <button class="time-toggle t-btn" id="t-btn-1825" onclick="updateTerminalChart(1825)">5Y</button>
                            </div>
                            <div class="flex bg-[var(--bg-stronger)] border border-[var(--border)] rounded-xl p-1">
                                <button class="time-toggle type-btn active" id="type-line" onclick="updateTerminalChartType('line')">Line</button>
                                <button class="time-toggle type-btn" id="type-candle" onclick="updateTerminalChartType('candle')">Candle</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 relative w-full h-full">
                        <canvas id="marketChart"></canvas>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif ($view === 'offer'): ?>
            
            <!-- OFFER HOME EQUITY VIEW (Redirect Pane) -->
            <div class="glass-pane max-w-4xl mx-auto p-12 sm:p-16 text-center mt-12 shadow-xl">
                <div class="w-20 h-20 bg-purple-500/10 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-8 border border-purple-500/20 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-6">Offer Home Equity</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Begin the algorithmic underwriting process to convert your property. Unlock liquidity by offering your physical real estate as tradable index shares on the REX network.
                </p>
                <button class="bg-[#2563eb] text-white border-none px-10 py-5 rounded-xl font-bold text-lg cursor-pointer transition-transform hover:-translate-y-1 shadow-[0_8px_24px_rgba(37,99,235,0.25)]" onclick="window.location.href='REX_UI_Questionnaire.php'">Initialize Underwriting &rarr;</button>
            </div>

        <?php elseif ($view === 'advanced'): ?>
            
            <!-- ADVANCED MARKET DATA VIEW (Finviz Treemap) -->
            <?php
                // Flatten all leaf node assets for Finviz style massive treemap
                $all_heatmap_assets = [];
                foreach($database as $cont => $countries) {
                    if ($cont === '_meta') continue;
                    foreach($countries as $country => $cities) {
                        if ($country === '_meta') continue;
                        foreach($cities as $city => $districts) {
                            if ($city === '_meta') continue;
                            if (isset($districts['ticker'])) { 
                                // Direct Market Leaf
                                $all_heatmap_assets[] = ['name' => $districts['ticker'], 'meta' => $districts, 'city' => $city, 'path' => urlencode("$cont|$country|$city")];
                            } else {
                                // City with Districts, use the city meta for the heatmap aggregate
                                $all_heatmap_assets[] = ['name' => $districts['_meta']['ticker'], 'meta' => $districts['_meta'], 'city' => $city, 'path' => urlencode("$cont|$country|$city")];
                            }
                        }
                    }
                }
                // Sort by absolute price
                usort($all_heatmap_assets, function($a, $b) {
                    return $b['meta']['price'] <=> $a['meta']['price'];
                });
            ?>
            <div class="max-w-[1500px] mx-auto mt-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-6">
                    <div class="glass-pane px-10 py-6 inline-block w-full md:w-auto">
                        <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-2">Advanced Market Intelligence</h2>
                        <p class="text-slate-600 dark:text-slate-400 font-mono text-sm tracking-wide">Price-weighted momentum map & AI tracking.</p>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row gap-8 h-auto xl:h-[800px]">
                    
                    <!-- Left Col: Massive Finviz Heatmap -->
                    <div class="w-full xl:w-3/4 bg-[#111827] border border-slate-800 shadow-2xl relative overflow-hidden h-[600px] xl:h-full flex flex-col p-1 rounded-sm">
                        <div class="flex flex-wrap w-full h-full content-start gap-[1px]">
                            <?php foreach($all_heatmap_assets as $item): ?>
                                <?php 
                                    $val = $item['meta']['change_val'];
                                    
                                    // Exact Finviz Treemap Colors
                                    if ($val <= -2.0) $bg = '#b91c1c'; 
                                    elseif ($val < 0) $bg = '#ef4444'; 
                                    elseif ($val >= 2.0) $bg = '#15803d'; 
                                    elseif ($val >= 0) $bg = '#22c55e'; 
                                    else $bg = '#475569';
                                    
                                    $weight = max(1, round($item['meta']['price'] / 1500));
                                ?>
                                <a href="?view=terminal&path=<?= $item['path'] ?>" 
                                   class="flex-shrink-0 flex-basis-auto flex flex-col items-center justify-center p-1 hover:brightness-125 transition-all cursor-pointer relative overflow-hidden group rounded-sm shadow-inner" 
                                   style="background-color: <?= $bg ?>; flex-grow: <?= $weight ?>; flex-basis: <?= $weight * 12 ?>px; min-width: 60px; min-height: 60px;">
                                    <span class="text-white font-bold text-xs sm:text-sm md:text-base tracking-wide drop-shadow-md z-10 group-hover:scale-105 transition-transform truncate px-1">
                                        <?= explode('-', $item['name'])[1] ?? $item['name'] ?>
                                    </span>
                                    <span class="text-white opacity-90 text-[10px] sm:text-xs font-mono drop-shadow-md z-10">
                                        <?= $item['meta']['change'] ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Col: Sentiment Matrix -->
                    <div class="w-full xl:w-1/4 glass-pane flex flex-col h-[600px] xl:h-full">
                        <div class="p-6 pb-4 border-b border-[var(--border)]">
                            <h3 class="font-bold text-xl text-slate-900 dark:text-white">AI Sentiment</h3>
                            <p class="text-xs text-[var(--text-muted)] font-semibold mt-1">Algorithmic confidence ranking</p>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4 space-y-2">
                            <?php
                                $sentiment_list = [];
                                $sentiment_list[] = ['name' => 'Global Market', 'ticker' => 'REX-GLOBAL-IDX', 'val' => $database['_meta']['change_val'], 'path' => ''];
                                
                                foreach($database as $cont => $countries) {
                                    if ($cont === '_meta') continue;
                                    $sentiment_list[] = ['name' => $cont, 'ticker' => $countries['_meta']['ticker'], 'val' => $countries['_meta']['change_val'], 'path' => urlencode($cont)];
                                    foreach($countries as $country => $cities) {
                                        if ($country === '_meta') continue;
                                        $sentiment_list[] = ['name' => $country, 'ticker' => $cities['_meta']['ticker'], 'val' => $cities['_meta']['change_val'], 'path' => urlencode("$cont|$country")];
                                    }
                                }

                                foreach($sentiment_list as $item): 
                                    $sent = getSentiment($item['val']);
                            ?>
                                <a href="?view=terminal&path=<?= $item['path'] ?>" class="block p-3 rounded-xl <?= $sent['bg'] ?> border transition-colors hover:brightness-110">
                                    <div class="flex justify-between items-start mb-1.5">
                                        <div class="font-bold text-sm text-slate-900 dark:text-white truncate max-w-[140px]"><?= $item['name'] ?></div>
                                        <div class="text-xs font-mono font-bold <?= $sent['color'] ?>"><?= $sent['score'] ?>/100</div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-[10px] font-mono text-[var(--text-muted)] truncate"><?= $item['ticker'] ?></div>
                                        <div class="text-[10px] font-bold uppercase tracking-widest <?= $sent['color'] ?>"><?= $sent['label'] ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

        <?php elseif ($view === 'portfolio'): ?>
            
            <!-- MY PORTFOLIO VIEW -->
            <?php
                // Split logic for clean tables
                $liquid_holdings = array_filter($portfolio_holdings, function($h) { return $h['status'] === 'Liquid'; });
                $vaulted_holdings = array_filter($portfolio_holdings, function($h) { return $h['status'] !== 'Liquid'; });
            ?>
            <div class="max-w-6xl mx-auto mt-8">
                
                <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div class="glass-pane px-10 py-6 inline-block">
                        <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-2">Portfolio Overview</h2>
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                            <p class="text-white font-mono text-sm tracking-wide bg-[#2563eb] px-2 py-0.5 rounded shadow-sm">Connected: 0x7F4...3A9B</p>
                        </div>
                    </div>
                </div>

                <!-- Top Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="glass-pane p-6">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Total Equity Value</div>
                        <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">$<?= number_format($current_portfolio_value, 2) ?></div>
                    </div>
                    <div class="glass-pane p-6">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">All-Time P/L</div>
                        <div class="text-2xl font-bold tracking-tight <?= $total_pl_pct >= 0 ? 'text-emerald-500' : 'text-rose-500' ?>">
                            <?= $total_pl_pct >= 0 ? '+' : '' ?>$<?= number_format($total_pl_abs, 2) ?> <br>
                            <span class="text-xl">(<?= number_format($total_pl_pct, 2) ?>%)</span>
                        </div>
                    </div>
                    <div class="glass-pane p-6 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Available Cash (USD)</div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">$<?= number_format($cash_balance, 2) ?></div>
                        </div>
                        <button class="mt-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold py-2 px-4 rounded-lg hover:-translate-y-0.5 transition-transform text-sm shadow-md">
                            + Deposit Funds
                        </button>
                    </div>
                </div>

                <!-- Chart Pane -->
                <div class="glass-pane p-8 mb-10 h-[400px] flex flex-col">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white">Historical Account Value</h3>
                        <div class="flex bg-[var(--bg-stronger)] border border-[var(--border)] rounded-xl p-1">
                            <button class="time-toggle p-btn" id="p-btn-7" onclick="updatePortChart(7)">1W</button>
                            <button class="time-toggle p-btn" id="p-btn-30" onclick="updatePortChart(30)">1M</button>
                            <button class="time-toggle p-btn" id="p-btn-365" onclick="updatePortChart(365)">1Y</button>
                            <button class="time-toggle p-btn active" id="p-btn-1825" onclick="updatePortChart(1825)">5Y</button>
                        </div>
                    </div>
                    <div class="flex-1 relative w-full h-full">
                        <canvas id="portfolioChart"></canvas>
                    </div>
                </div>

                <div class="flex flex-col gap-10">
                    
                    <!-- Liquid Holdings Table -->
                    <div class="glass-pane p-8">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 border-b border-[var(--border)] pb-4 flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Available Liquid Equity
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Asset</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Shares</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Price (Avg / Live)</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider text-right">Live P/L</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    <?php foreach ($liquid_holdings as $holding): 
                                        $pl_val = ($holding['current_price'] - $holding['avg_price']) * $holding['shares'];
                                        $pl_pct = (($holding['current_price'] - $holding['avg_price']) / $holding['avg_price']) * 100;
                                    ?>
                                    <tr class="hover:bg-[var(--bg-subtle)] transition-colors">
                                        <td class="py-4 font-bold text-slate-900 dark:text-white"><?= $holding['ticker'] ?></td>
                                        <td class="py-4">
                                            <span class="px-3 py-1.5 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Liquid</span>
                                        </td>
                                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-300"><?= number_format($holding['shares'], 2) ?></td>
                                        <td class="py-4">
                                            <div class="text-sm font-mono text-slate-600 dark:text-slate-400">Avg: $<?= number_format($holding['avg_price'], 2) ?></div>
                                            <div class="text-sm font-mono font-bold text-slate-900 dark:text-white">Live: $<?= number_format($holding['current_price'], 2) ?></div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="font-bold <?= $pl_val >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' ?>">
                                                <?= $pl_val >= 0 ? '+' : '' ?>$<?= number_format($pl_val, 2) ?>
                                            </div>
                                            <div class="text-sm font-semibold <?= $pl_pct >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' ?>">
                                                <?= $pl_pct >= 0 ? '+' : '' ?><?= number_format($pl_pct, 2) ?>%
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <button class="bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold px-4 py-2 rounded-lg text-sm hover:bg-blue-500/20 transition-colors">Trade</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vaulted Holdings Table -->
                    <div class="glass-pane p-8">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 border-b border-[var(--border)] pb-4 flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-purple-500"></span> Vaulted (Locked) Equity
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Asset</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Lockup Status</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Shares</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Price (Avg / Live)</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider text-right">Live P/L</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    <?php foreach ($vaulted_holdings as $holding): 
                                        $pl_val = ($holding['current_price'] - $holding['avg_price']) * $holding['shares'];
                                        $pl_pct = (($holding['current_price'] - $holding['avg_price']) / $holding['avg_price']) * 100;
                                    ?>
                                    <tr class="hover:bg-[var(--bg-subtle)] transition-colors">
                                        <td class="py-4 font-bold text-slate-900 dark:text-white"><?= $holding['ticker'] ?></td>
                                        <td class="py-4">
                                            <span class="px-3 py-1.5 rounded-md text-xs font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                                🔒 Locked (<?= $holding['days_left'] ?> Days Left)
                                            </span>
                                        </td>
                                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-300"><?= number_format($holding['shares'], 2) ?></td>
                                        <td class="py-4">
                                            <div class="text-sm font-mono text-slate-600 dark:text-slate-400">Avg: $<?= number_format($holding['avg_price'], 2) ?></div>
                                            <div class="text-sm font-mono font-bold text-slate-900 dark:text-white">Live: $<?= number_format($holding['current_price'], 2) ?></div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="font-bold <?= $pl_val >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' ?>">
                                                <?= $pl_val >= 0 ? '+' : '' ?>$<?= number_format($pl_val, 2) ?>
                                            </div>
                                            <div class="text-sm font-semibold <?= $pl_pct >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' ?>">
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
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Asset</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Shares</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider">Price Executed</th>
                                        <th class="pb-4 text-xs font-bold text-slate-800 dark:text-slate-400 uppercase tracking-wider text-right">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    <?php foreach ($portfolio_trades as $trade): ?>
                                    <tr class="hover:bg-[var(--bg-subtle)] transition-colors">
                                        <td class="py-4 text-sm font-semibold text-slate-600 dark:text-slate-400"><?= $trade['date'] ?></td>
                                        <td class="py-4">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded border 
                                                <?= $trade['type'] == 'BUY' ? 'text-emerald-600 dark:text-emerald-400 border-emerald-500/30 bg-emerald-500/10' : 
                                                   ($trade['type'] == 'SELL' ? 'text-rose-600 dark:text-rose-400 border-rose-500/30 bg-rose-500/10' : 
                                                   'text-purple-600 dark:text-purple-400 border-purple-500/30 bg-purple-500/10') ?>">
                                                <?= $trade['type'] ?>
                                            </span>
                                        </td>
                                        <td class="py-4 font-bold text-slate-900 dark:text-white"><?= $trade['ticker'] ?></td>
                                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-100"><?= number_format($trade['shares'], 2) ?></td>
                                        <td class="py-4 font-mono text-sm text-slate-600 dark:text-slate-400">$<?= number_format($trade['price'], 2) ?></td>
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

        <?php elseif ($view === 'chain'): ?>
            
            <!-- TRANSACTION CHAIN LEDGER -->
            <div class="max-w-5xl mx-auto mt-8">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
                    <div class="glass-pane px-10 py-6 inline-block w-full md:w-auto">
                        <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-3">Live Transaction Chain</h2>
                        <p class="text-lg text-slate-600 dark:text-slate-400">Real-time cryptographic ledger of global market settlements.</p>
                    </div>
                    <div class="flex items-center gap-3 bg-[var(--bg-pane)] px-4 py-2 rounded-full border border-[var(--border)] shadow-sm">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wide">NETWORK LIVE</span>
                    </div>
                </div>

                <!-- Ledger Filtering Pane -->
                <form method="GET" action="" class="glass-pane p-6 mb-12 flex flex-col md:flex-row gap-6 items-end">
                    <input type="hidden" name="view" value="chain">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter by Asset / Index</label>
                        <select name="filter_ticker" class="w-full bg-[var(--bg-subtle)] border border-[var(--border)] text-[var(--text-main)] font-semibold p-3.5 rounded-xl outline-none focus:border-[var(--brand-color)] transition-all cursor-pointer">
                            <option value="ALL" <?= $filter_ticker === 'ALL' ? 'selected' : '' ?>>🌐 Global Market (All Assets)</option>
                            <?php foreach ($all_tickers as $tick): ?>
                                <option value="<?= $tick ?>" <?= $filter_ticker === $tick ? 'selected' : '' ?>><?= $tick ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter by Event Type</label>
                        <select name="filter_type" class="w-full bg-[var(--bg-subtle)] border border-[var(--border)] text-[var(--text-main)] font-semibold p-3.5 rounded-xl outline-none focus:border-[var(--brand-color)] transition-all cursor-pointer">
                            <option value="ALL" <?= $filter_type === 'ALL' ? 'selected' : '' ?>>All Event Types</option>
                            <option value="BUY" <?= $filter_type === 'BUY' ? 'selected' : '' ?>>Buy Executions</option>
                            <option value="SELL" <?= $filter_type === 'SELL' ? 'selected' : '' ?>>Sell Executions</option>
                            <option value="SHORT" <?= $filter_type === 'SHORT' ? 'selected' : '' ?>>Short Positions</option>
                            <option value="VAULT_LOCK" <?= $filter_type === 'VAULT_LOCK' ? 'selected' : '' ?>>Vault Locks</option>
                            <option value="TOKENIZE" <?= $filter_type === 'TOKENIZE' ? 'selected' : '' ?>>Asset Tokenizations</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold py-3.5 px-8 rounded-xl shadow-lg transition-transform hover:-translate-y-1 border-none">
                        Query Ledger
                    </button>
                </form>

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
                                            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                                Genesis Node
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-sm font-mono text-[var(--text-light)] copy-btn" onclick="copyToClipboard('<?= $block['hash'] ?>')">
                                        Hash: <?= $block['hash'] ?>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
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
                                            <span class="text-xs font-bold px-3 py-1 rounded-lg border <?= $tx['color'] ?>">
                                                <?= $tx['type'] ?>
                                            </span>
                                        </div>
                                        <div class="w-1/2 text-base font-medium text-slate-800 dark:text-slate-200">
                                            <?= $tx['detail'] ?>
                                        </div>
                                        <div class="w-1/4 text-right font-mono text-xs text-[var(--text-muted)] copy-btn justify-end" onclick="copyToClipboard('<?= $tx['txid'] ?>')">
                                            TXID: <?= substr($tx['txid'], 0, 10) ?>...
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($view === 'about'): ?>
            
            <!-- ABOUT REX VIEW -->
            <div class="max-w-5xl mx-auto mt-12 mb-20 space-y-8">
                
                <div class="glass-pane overflow-hidden">
                    <div class="p-12 sm:p-16 border-b border-[var(--border)] relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-color rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 opacity-20"></div>
                        <h2 class="text-4xl font-serif font-black text-slate-900 dark:text-white mb-6 relative z-10">About REX</h2>
                        <p class="text-xl text-slate-700 dark:text-slate-300 font-medium leading-relaxed max-w-2xl relative z-10">
                            Transforming home equity into accessible, secure, and highly liquid standardized shares.
                        </p>
                    </div>
                    
                    <div class="p-12 sm:p-16 space-y-16">
                        <!-- Problem Section -->
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="w-12 h-12 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center flex-shrink-0 border border-rose-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">The Real Estate Liquidity Trap</h3>
                                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                                    Historically, real estate has been the most illiquid and gatekept asset class on the planet. Millions of homeowners are trapped inside massive, untappable wealth unless they take on high-interest loans or completely uproot their lives by selling. Concurrently, everyday investors are completely priced out of lucrative regional markets due to massive down payment requirements.
                                </p>
                            </div>
                        </div>

                        <!-- Solution Section -->
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="w-12 h-12 bg-blue-500/10 text-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">The REX Solution: Standardized Shares</h3>
                                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                                    REX (Residential Property Exchange) bridges this gap safely and intuitively. We algorithmically appraise physical properties and standardize their legal equity into fractional <strong>Shares</strong>. These shares form massive, tradable regional Indices. 
                                    <br><br>
                                    If you own a home, you can underwrite it and instantly offer your shares to the market for liquid cash without taking on debt. If you are an investor, you can easily buy shares in specific districts in Tokyo, London, or New York starting with just a few dollars.
                                </p>
                            </div>
                        </div>

                        <!-- Safety Section -->
                        <div class="bg-emerald-500/5 border border-emerald-500/20 p-8 rounded-2xl flex flex-col md:flex-row gap-8 items-start shadow-inner">
                            <div class="w-12 h-12 bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center flex-shrink-0 border border-emerald-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Uncompromising Protocol Safety</h3>
                                <p class="text-lg text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                                    A secure market requires rigid fundamentals. To protect all index participants, our underwriting engine strictly limits property tokenization to a <strong>maximum of 25% of the estimated home value</strong>. This aggressive over-collateralization mathematically ensures that the shares backing the index are unconditionally safe from standard market downturns and severe mortgage default events.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Partners -->
                <div class="glass-pane p-12 sm:p-16">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Strategic Infrastructure Partners</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="aspect-video bg-[var(--bg-subtle)] border border-[var(--border)] rounded-xl flex items-center justify-center p-4 hover:-translate-y-1 transition-transform cursor-pointer">
                            <div class="font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-xs text-center flex flex-col items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                Global<br>Appraisal API
                            </div>
                        </div>
                        <div class="aspect-video bg-[var(--bg-subtle)] border border-[var(--border)] rounded-xl flex items-center justify-center p-4 hover:-translate-y-1 transition-transform cursor-pointer">
                            <div class="font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-xs text-center flex flex-col items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                Decentralized<br>Oracle Network
                            </div>
                        </div>
                        <div class="aspect-video bg-[var(--bg-subtle)] border border-[var(--border)] rounded-xl flex items-center justify-center p-4 hover:-translate-y-1 transition-transform cursor-pointer">
                            <div class="font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-xs text-center flex flex-col items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Protocol<br>Audit Firm
                            </div>
                        </div>
                        <div class="aspect-video bg-[var(--bg-subtle)] border border-[var(--border)] rounded-xl flex items-center justify-center p-4 hover:-translate-y-1 transition-transform cursor-pointer">
                            <div class="font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-xs text-center flex flex-col items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Base L1<br>Blockchain
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </main>

    <!-- Global JavaScript for Terminal Logic -->
    <script>
        // Copy to Clipboard Utility
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const toast = document.getElementById('toast');
                toast.classList.add('show');
                setTimeout(() => { toast.classList.remove('show'); }, 2500);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }

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

        // Auth Modal Handlers (Clears inputs when opened)
        function openAuthModal(type) {
            const title = type === 'login' ? 'Log In' : 'Sign Up';
            document.getElementById('authTitle').innerText = title;
            const uInput = document.getElementById('authUsername');
            const pInput = document.getElementById('authPassword');
            if(uInput) uInput.value = '';
            if(pInput) pInput.value = '';
            document.getElementById('authModal').classList.add('active');
        }
        
        function closeAuthModal() {
            document.getElementById('authModal').classList.remove('active');
        }

        // ----------------------------------------------------
        // TRADE PANEL & TERMINAL CHART LOGIC
        // ----------------------------------------------------
        <?php if ($view === 'terminal' && $trade_target): ?>
            const indexPrice = <?= $trade_target['price'] ?>;
            const rawChartData = <?= json_encode($chartData) ?>;
            let terminalChart = null;
            let currentTerminalDays = 30;
            let currentTerminalType = 'line';

            function syncInputs(type, source) {
                let currentUsdVal = 0;

                if (type === 'short') {
                    const marginInput = document.getElementById('input-short-margin');
                    const sharesInput = document.getElementById('input-short-shares');
                    
                    if (source === 'margin') {
                        if (marginInput.value !== '') {
                            currentUsdVal = parseFloat(marginInput.value);
                            let totalPosition = currentUsdVal * 2; 
                            sharesInput.value = (totalPosition / indexPrice).toFixed(4);
                        } else { sharesInput.value = ''; }
                    } else if (source === 'shares') {
                        if (sharesInput.value !== '') {
                            let totalPosition = parseFloat(sharesInput.value) * indexPrice;
                            currentUsdVal = totalPosition * 0.50;
                            marginInput.value = currentUsdVal.toFixed(2); 
                        } else { marginInput.value = ''; }
                    }

                    let totalPos = currentUsdVal * 2;
                    document.getElementById('short-total-size').innerText = '$' + totalPos.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('short-maint-margin').innerText = '$' + (totalPos * 0.20).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    return; 
                }

                const usdInput = document.getElementById('input-' + type + '-usd');
                const sharesInput = document.getElementById('input-' + type + '-shares');
                
                if (source === 'usd') {
                    if (usdInput.value !== '') {
                        currentUsdVal = parseFloat(usdInput.value);
                        sharesInput.value = (currentUsdVal / indexPrice).toFixed(4);
                    } else sharesInput.value = '';
                } else {
                    if (sharesInput.value !== '') {
                        currentUsdVal = parseFloat(sharesInput.value) * indexPrice;
                        usdInput.value = currentUsdVal.toFixed(2);
                    } else usdInput.value = '';
                }

                // Sync dynamic fee calculation for Buy/Sell
                const feeEl = document.getElementById('fee-' + type);
                if (feeEl) {
                    if (currentUsdVal > 0) {
                        const fee = Math.max(1, currentUsdVal * 0.001);
                        feeEl.innerText = '$' + fee.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (0.1%)';
                    } else {
                        feeEl.innerText = '$0.00 (0.1%)';
                    }
                }
            }

            function openPanel() {
                document.getElementById('overlay').classList.add('active');
                document.getElementById('tradePanel').classList.add('active');
                const chartPanel = document.getElementById('chartPanel');
                if (chartPanel) chartPanel.classList.add('active');
            }

            function closeTradeWindows() {
                document.getElementById('overlay').classList.remove('active');
                document.getElementById('tradePanel').classList.remove('active');
                const chartPanel = document.getElementById('chartPanel');
                if (chartPanel) chartPanel.classList.remove('active');
            }

            function closePanel() { closeTradeWindows(); }

            function switchTab(tabId) {
                document.querySelectorAll('.trade-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                event.currentTarget.classList.add('active');
                document.getElementById('tab-' + tabId).classList.add('active');
            }

            function updateTerminalChart(days) {
                currentTerminalDays = days;
                document.querySelectorAll('.t-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('t-btn-' + days)?.classList.add('active');
                renderTerminalChart();
            }

            function updateTerminalChartType(type) {
                currentTerminalType = type;
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('type-' + type)?.classList.add('active');
                renderTerminalChart();
            }

            function renderTerminalChart() {
                if(!terminalChart) return;
                const dataSlice = rawChartData.slice(-currentTerminalDays);
                const labels = dataSlice.map(d => d.d);
                
                terminalChart.data.labels = labels;
                
                if (currentTerminalType === 'candle') {
                    terminalChart.data.datasets = [
                        {
                            type: 'bar',
                            label: 'Wick',
                            data: dataSlice.map(d => [d.l, d.h]),
                            backgroundColor: dataSlice.map(d => d.c >= d.o ? '#10b981' : '#f43f5e'),
                            barThickness: 2,
                            grouped: false
                        },
                        {
                            type: 'bar',
                            label: 'Body',
                            data: dataSlice.map(d => [d.o, d.c]),
                            backgroundColor: dataSlice.map(d => d.c >= d.o ? '#10b981' : '#f43f5e'),
                            barThickness: 'flex',
                            grouped: false
                        }
                    ];
                } else {
                    terminalChart.data.datasets = [{
                        type: 'line',
                        label: 'Index Price (USD)',
                        data: dataSlice.map(d => d.c),
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
                    }];
                }
                terminalChart.update();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('marketChart');
                if(ctx) {
                    Chart.defaults.color = '#94a3b8';
                    Chart.defaults.font.family = 'Inter, sans-serif';
                    
                    terminalChart = new Chart(ctx.getContext('2d'), {
                        data: { labels: [], datasets: [] },
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
                                            let val = Array.isArray(context.raw) ? context.raw[1] : context.raw;
                                            return '$' + val.toLocaleString(undefined, {minimumFractionDigits: 2});
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
                    renderTerminalChart();
                }
            });
        <?php endif; ?>

        // ----------------------------------------------------
        // PORTFOLIO CHART LOGIC
        // ----------------------------------------------------
        <?php if ($view === 'portfolio'): ?>
            const rawPortData = <?= json_encode($port_chart_data) ?>;
            let portChartInst = null;

            function updatePortChart(days) {
                document.querySelectorAll('.p-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('p-btn-' + days)?.classList.add('active');
                
                const slice = rawPortData.slice(-days);
                if(portChartInst) {
                    portChartInst.data.labels = slice.map(d => d.d);
                    portChartInst.data.datasets[0].data = slice.map(d => d.c);
                    portChartInst.update();
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('portfolioChart');
                if(ctx) {
                    Chart.defaults.color = '#94a3b8';
                    Chart.defaults.font.family = 'Inter, sans-serif';
                    
                    portChartInst = new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: rawPortData.map(d => d.d),
                            datasets: [{
                                label: 'Account Value (USD)',
                                data: rawPortData.map(d => d.c),
                                borderColor: '#10b981', 
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
                    updatePortChart(1825);
                }
            });
        <?php endif; ?>

        // ----------------------------------------------------
        // GLOBE.GL LOGIC
        // ----------------------------------------------------
        <?php if ($view === 'terminal' && $node_type === 0): ?>
            let myGlobe = null;
            const globeMarkerData = <?= json_encode($globe_markers) ?>;

            const arcsData = [];
            for(let i=0; i<30; i++) {
                let start = globeMarkerData[Math.floor(Math.random() * globeMarkerData.length)];
                let end = globeMarkerData[Math.floor(Math.random() * globeMarkerData.length)];
                if (start && end && start !== end) {
                    arcsData.push({
                        startLat: start.lat, startLng: start.lng,
                        endLat: end.lat, endLng: end.lng,
                        color: ['#3b82f6', '#10b981']
                    });
                }
            }

            function toggleGlobe() {
                const container = document.getElementById('globe-container');
                if (container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    if (!myGlobe) {
                        myGlobe = Globe()
                        (document.getElementById('globe-container'))
                        .globeImageUrl('//unpkg.com/three-globe/example/img/earth-dark.jpg')
                        .bumpImageUrl('//unpkg.com/three-globe/example/img/earth-topology.png')
                        .backgroundColor('rgba(0,0,0,0)')
                        .htmlElementsData(globeMarkerData)
                        .htmlElement(d => {
                            const el = document.createElement('div');
                            const isUp = d.val >= 0;
                            const colorClass = isUp ? 'text-emerald-400' : 'text-rose-400';
                            el.innerHTML = `
                                <div class="bg-slate-900/90 backdrop-blur-md border border-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-lg whitespace-nowrap flex flex-col items-center cursor-pointer transition-transform hover:scale-110 hover:border-brand-color z-50">
                                    <span class="text-blue-400 mb-0.5 tracking-wider">${d.ticker}</span> 
                                    <span class="${colorClass}">${d.change}</span>
                                </div>
                            `;
                            el.style.pointerEvents = 'auto';
                            el.onclick = () => window.location.href = '?view=terminal&path=' + d.path;
                            return el;
                        })
                        .arcsData(arcsData)
                        .arcColor('color')
                        .arcDashLength(0.4)
                        .arcDashGap(0.2)
                        .arcDashInitialGap(() => Math.random())
                        .arcDashAnimateTime(1500)
                        .arcStroke(0.6);
                        
                        myGlobe.controls().autoRotate = true;
                        myGlobe.controls().autoRotateSpeed = 0.8;
                        myGlobe.controls().enableZoom = true; 
                        myGlobe.pointOfView({ lat: 20, lng: -40, altitude: 2.2 });
                    }
                } else {
                    container.classList.add('hidden');
                }
            }
        <?php endif; ?>
    </script>
</body>
</html>