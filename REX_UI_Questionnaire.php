<?php

require_once 'REX_DB_Handler.php';

// Handle form submission and file uploads
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $consent = isset($_POST['consent_disclaimer']) ? 1 : 0;

    function get_val($key) {
        return (isset($_POST[$key]) && trim($_POST[$key]) !== '') ? trim($_POST[$key]) : null;
    }

    // Safely assign all variables
    $legal_name           = get_val('legal_name');
    $contact_email        = get_val('contact_email');
    $street_address       = get_val('street_address');
    $postal_code          = get_val('postal_code');
    
    $city                 = get_val('city');
    $state_region         = get_val('state_region');
    $country              = get_val('country');
    $property_type        = get_val('property_type');
    $zoning_class         = get_val('zoning_class');
    $tenure_type          = get_val('tenure_type');
    $leasehold_years      = get_val('leasehold_years'); 
    
    $official_living_area = get_val('official_living_area');
    $official_lot_size    = get_val('official_lot_size');
    $year_built           = get_val('year_built');
    $last_renovation_year = get_val('last_renovation_year');
    $total_bedrooms       = get_val('total_bedrooms');
    $wet_rooms            = get_val('wet_rooms');
    $roof_material        = get_val('roof_material');
    $roof_age             = get_val('roof_age');
    $hvac_type            = get_val('hvac_type');
    $hvac_age             = get_val('hvac_age');
    $parking_spaces       = get_val('parking_spaces');
    $has_pool             = get_val('has_pool');
    $waterfront_access    = get_val('waterfront_access');

    $outstanding_debt     = get_val('outstanding_debt');
    $annual_tax           = get_val('annual_tax');
    $annual_insurance     = get_val('annual_insurance');
    $monthly_maintenance  = get_val('monthly_maintenance');
    $rental_income        = get_val('rental_income');

    $legal_entity_type    = get_val('legal_entity_type');
    $has_active_liens     = get_val('has_active_liens');
    $has_easements        = get_val('has_easements');
    $has_pending_lit      = get_val('has_pending_litigation');
    $has_rental_restr     = get_val('has_rental_restrictions');
    $energy_rating        = get_val('energy_rating');
    $climate_risk_zone    = get_val('climate_risk_zone');
    $has_solar_panels     = get_val('has_solar_panels');
    $has_env_hazards      = get_val('has_environmental_hazards');
    
    $equity_target        = get_val('equity_target');

    // Extract primary ownership document
    $ownership_blob = null;
    if (isset($_FILES['ownership_doc']) && $_FILES['ownership_doc']['error'] === UPLOAD_ERR_OK) {
        $ownership_blob = file_get_contents($_FILES['ownership_doc']['tmp_name']);
    }

    // =========================================================================
    // EXECUTE 3-TABLE INSERTION WITH TRANSACTION SAFETY
    // =========================================================================
    
    $conn->begin_transaction();

    try {
        // --- TABLE 1: USER MANAGEMENT ---
        $owner_id = null;
        
        // Check if user already exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $contact_email);
        $stmt_check->execute();
        $stmt_check->bind_result($existing_id);
        if ($stmt_check->fetch()) {
            $owner_id = $existing_id;
        }
        $stmt_check->close();

        // Create new user if they don't exist
        if (!$owner_id) {
            $role = 'homeowner';
            $stmt_user = $conn->prepare("INSERT INTO users (legal_name, email, role) VALUES (?, ?, ?)");
            $stmt_user->bind_param("sss", $legal_name, $contact_email, $role);
            $stmt_user->execute();
            $owner_id = $conn->insert_id;
            $stmt_user->close();
        }

        // --- TABLE 2: PUBLIC MARKET DATA ---
        $query_public = "INSERT INTO property_public_market (
            owner_id, city, state_region, country, property_type, zoning_class, tenure_type, leasehold_years,
            official_living_area, official_lot_size, year_built, last_renovation_year, total_bedrooms, wet_rooms, roof_material, roof_age, hvac_type, hvac_age, parking_spaces, has_pool, waterfront_access,
            outstanding_debt, annual_tax, annual_insurance, monthly_maintenance, rental_income,
            legal_entity_type, has_active_liens, has_easements, has_pending_litigation, has_rental_restrictions, energy_rating, climate_risk_zone, has_solar_panels, has_environmental_hazards,
            equity_target
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_public = $conn->prepare($query_public);
        
        // 36 Parameters mapped perfectly: issssssiddiiiisisiissdddddsssssssssd
        $stmt_public->bind_param("issssssiddiiiisisiissdddddsssssssssd", 
            $owner_id, $city, $state_region, $country, $property_type, $zoning_class, $tenure_type, $leasehold_years,
            $official_living_area, $official_lot_size, $year_built, $last_renovation_year, $total_bedrooms, $wet_rooms, $roof_material, $roof_age, $hvac_type, $hvac_age, $parking_spaces, $has_pool, $waterfront_access,
            $outstanding_debt, $annual_tax, $annual_insurance, $monthly_maintenance, $rental_income,
            $legal_entity_type, $has_active_liens, $has_easements, $has_pending_lit, $has_rental_restr, $energy_rating, $climate_risk_zone, $has_solar_panels, $has_env_hazards,
            $equity_target
        );
        $stmt_public->execute();
        
        // Capture the generated property ID
        $property_id = $conn->insert_id;
        $stmt_public->close();

        // --- TABLE 3: PRIVATE COMPLIANCE DATA ---
        $query_private = "INSERT INTO property_private_compliance (
            property_id, street_address, postal_code, consent_disclaimer, ownership_doc
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt_private = $conn->prepare($query_private);
        
        // 5 Parameters (Parameter index 4 is the blob: issib)
        $stmt_private->bind_param("issib", $property_id, $street_address, $postal_code, $consent, $ownership_blob);
        
        if ($ownership_blob !== null) {
            $stmt_private->send_long_data(4, $ownership_blob);
        }
        
        $stmt_private->execute();
        $stmt_private->close();

        // Everything succeeded, commit the changes to the database
        $conn->commit();
        $submitted = true;

    } catch (Exception $e) {
        // If anything fails (like a bad upload), cancel all queries so we don't get partial data
        $conn->rollback();
        die("System Exception: Unable to securely process asset underwriting. Error: " . $e->getMessage());
    }
}

// Updated Structured Questionnaire
$questionnaire = [
    [
        "step" => 1,
        "title" => "Compliance & Mutual Security",
        "is_disclaimer" => true,
        "content" => "We require comprehensive data regarding your home equity to ensure the integrity of our valuation process and prevent fraudulent activities. As product providers, we hold a strict fiduciary duty to protect our investors from exposure to defrauded assets. Furthermore, this rigorous policy serves to defend your own interests, ensuring you enter a secure ecosystem as a prospective shareowner yourself. Transparency in asset backing is the cornerstone of our mutual security.",
        "questions" => [
            ["name" => "consent_disclaimer", "label" => "I acknowledge the security policy and confirm I am the authorized owner or legal representative of this property.", "type" => "checkbox", "required" => true],
        ]
    ],
    [
        "step" => 2,
        "title" => "Private Identity & Location",
        "subtitle" => "Direct ownership links and exact coordinates.",
        "is_personal" => true,
        "questions" => [
            ["name" => "legal_name", "label" => "Full Legal Name (Matching the Deed)", "type" => "text", "required" => true],
            ["name" => "contact_email", "label" => "Secure Contact Email", "type" => "email", "required" => true],
            ["name" => "street_address", "label" => "Full Legal Street Address", "type" => "text", "required" => true],
            ["name" => "postal_code", "label" => "Postal / Zip Code", "type" => "text", "required" => true],
        ]
    ],
    [
        "step" => 3,
        "title" => "Public Market Classification",
        "subtitle" => "Regional zoning and fundamental ownership type.",
        "questions" => [
            ["name" => "city", "label" => "City / Municipality", "type" => "text", "required" => true],
            ["name" => "state_region", "label" => "State / Region / Province", "type" => "text", "required" => true],
            ["name" => "country", "label" => "Country", "type" => "text", "required" => true],
            ["name" => "property_type", "label" => "Primary Property Classification", "type" => "select", "options" => ["Single-Family Detached", "Condominium / Apartment", "Townhouse / Terraced", "Multi-Family (2-4 Units)", "Mixed-Use"], "required" => true],
            ["name" => "zoning_class", "label" => "Current Zoning Class", "type" => "select", "options" => ["Residential", "Commercial", "Agricultural", "Historic District", "Mixed-Use"]],
            ["name" => "tenure_type", "label" => "Ownership Tenure", "type" => "select", "options" => ["Freehold (Fee Simple)", "Leasehold"], "required" => true],
            ["name" => "leasehold_years", "label" => "If Leasehold, Years Remaining", "type" => "number"],
        ]
    ],
    [
        "step" => 4,
        "title" => "Structural Attributes",
        "subtitle" => "Strict quantitative build metrics.",
        "questions" => [
            ["name" => "official_living_area", "label" => "Gross Living Area (m²)", "type" => "number", "step" => "0.1", "required" => true],
            ["name" => "official_lot_size", "label" => "Total Plot / Land Size (m²)", "type" => "number", "step" => "0.1", "required" => true],
            ["name" => "year_built", "label" => "Original Year of Construction", "type" => "number", "placeholder" => "YYYY", "required" => true],
            ["name" => "last_renovation_year", "label" => "Year of Last Major Permitted Renovation", "type" => "number", "placeholder" => "Leave blank if none"],
            ["name" => "total_bedrooms", "label" => "Total Bedroom Count", "type" => "number", "required" => true],
            ["name" => "wet_rooms", "label" => "Total Bathroom/Wet Room Count", "type" => "number", "required" => true],
            ["name" => "roof_material", "label" => "Primary Roof Material", "type" => "select", "options" => ["Asphalt Shingle", "Ceramic / Clay Tile", "Metal", "Flat / Built-Up", "Wood / Slate", "Other"]],
            ["name" => "roof_age", "label" => "Estimated Roof Age (Years)", "type" => "number"],
            ["name" => "hvac_type", "label" => "Climate Control System", "type" => "select", "options" => ["Central Air / Forced Air", "Radiator / Boiler", "Heat Pump", "Ductless Mini-Splits", "None / Window Units"]],
            ["name" => "hvac_age", "label" => "Estimated HVAC Age (Years)", "type" => "number"],
            ["name" => "parking_spaces", "label" => "Dedicated Parking Spaces", "type" => "number", "defaultValue" => "0"],
            ["name" => "has_pool", "label" => "Contains In-Ground Pool?", "type" => "select", "options" => ["No", "Yes"]],
            ["name" => "waterfront_access", "label" => "Direct Waterfront Access?", "type" => "select", "options" => ["No", "Yes"]],
        ]
    ],
    [
        "step" => 5,
        "title" => "Financial & Yield Metrics",
        "subtitle" => "Baseline fiscal obligations and dividend projection data.",
        "questions" => [
            ["name" => "outstanding_debt", "label" => "Total Outstanding Mortgages/Loans ($)", "type" => "number", "required" => true, "placeholder" => "Enter 0 if fully paid off"],
            ["name" => "annual_tax", "label" => "Annual Municipal Property Tax ($)", "type" => "number"],
            ["name" => "annual_insurance", "label" => "Annual Property/Hazard Insurance ($)", "type" => "number"],
            ["name" => "monthly_maintenance", "label" => "Average Monthly HOA/Maintenance ($)", "type" => "number", "defaultValue" => "0"],
            ["name" => "rental_income", "label" => "Current Gross Annual Rent Yield ($)", "type" => "number", "placeholder" => "Leave blank if owner-occupied"],
        ]
    ],
    [
        "step" => 6,
        "title" => "Risk & ESG Reporting",
        "subtitle" => "Strict regulatory and environmental compliance.",
        "questions" => [
            ["name" => "legal_entity_type", "label" => "Titled Entity Status", "type" => "select", "options" => ["Individual / Sole Owner", "Joint Tenancy", "LLC / Corporate", "Trust"]],
            ["name" => "has_active_liens", "label" => "Are there active liens or judgments?", "type" => "select", "options" => ["No", "Yes"]],
            ["name" => "has_easements", "label" => "Are there known public right-of-ways/easements?", "type" => "select", "options" => ["No", "Yes"]],
            ["name" => "has_pending_litigation", "label" => "Is there pending real estate litigation?", "type" => "select", "options" => ["No", "Yes"]],
            ["name" => "has_rental_restrictions", "label" => "Are there strict short/long-term rental bans?", "type" => "select", "options" => ["No", "Yes"]],
            ["name" => "energy_rating", "label" => "Official Energy Rating", "type" => "select", "options" => ["Unknown / Unrated", "A", "B", "C", "D", "E", "F", "G"]],
            ["name" => "climate_risk_zone", "label" => "Known Climate Risk Zone", "type" => "select", "options" => ["None / Low Risk", "Flood Zone", "Wildfire Zone", "Fault Line / Seismic"]],
            ["name" => "has_solar_panels", "label" => "Installed Solar Energy System?", "type" => "select", "options" => ["No", "Yes (Owned)", "Yes (Leased)"]],
            ["name" => "has_environmental_hazards", "label" => "Known Hazards (Asbestos, Lead, etc.)?", "type" => "select", "options" => ["No", "Yes"]],
        ]
    ],
    [
        "step" => 7,
        "title" => "Tokenization & Documents",
        "subtitle" => "Define your market entry and upload compliance verification.",
        "is_personal" => true,
        "questions" => [
            ["name" => "equity_target", "label" => "Target Equity Tokenization (%)", "type" => "number", "step" => "0.1", "max" => "25", "placeholder" => "Maximum allowable is 25.0%", "required" => true],
            ["name" => "ownership_doc", "label" => "Upload Official Ownership Paper / Deed", "type" => "file", "accept" => ".pdf,.jpg,.jpeg,.png", "required" => true],
            ["name" => "other_documents", "label" => "Upload Other Relevant Home Documents (Optional)", "type" => "file", "accept" => ".pdf,.jpg,.jpeg,.png", "multiple" => true],
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Equity Questionnaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        body { transition: background-color 0.4s, color 0.4s; background-color: #e2e8f0; font-size: 1.05rem; }
        .dark body { background-color: #0c0a09; } 

        .bg-canvas {
            position: fixed; top: -20vh; left: -20vw; width: 140vw; height: 140vh; 
            z-index: -2; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #050505; 
        }
        .netflix-grid {
            display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px;
            transform: rotate(-18deg) scale(1.6); width: 160vw; filter: blur(3px); opacity: 0.6; transition: opacity 0.5s, filter 0.5s;
        }
        .dark .netflix-grid { opacity: 0.3; filter: blur(4px); }
        .netflix-img {
            width: 100%; aspect-ratio: 16 / 9; background-size: cover; background-position: center;
            border-radius: 4px; background-color: #334155; box-shadow: 0 4px 15px rgba(0,0,0,0.8);
        }

        .light-halo {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 120vw; height: 120vh;
            background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 60%); z-index: -1; pointer-events: none;
        }
        .dark .light-halo { background: radial-gradient(circle, rgba(210, 180, 140, 0.05) 0%, rgba(12, 10, 9, 0) 70%); }

        .contract-pane {
            position: relative; background-color: var(--paper-bg, #f9f8f3);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.2), 0 0 40px rgba(255,255,255,0.4);
        }
        .dark .contract-pane {
            --paper-bg: #27231f; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), 0 0 60px -15px rgba(210, 180, 140, 0.05);
        }
        .contract-inner-border {
            position: absolute; top: 8px; bottom: 8px; left: 8px; right: 8px;
            border: 1px solid rgba(100, 116, 139, 0.3); outline: 1px solid rgba(100, 116, 139, 0.15); outline-offset: -4px; pointer-events: none; z-index: 1;
        }
        .contract-pane::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; opacity: 0.6;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='1.2' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            z-index: 0; mix-blend-mode: multiply;
        }
        .dark .contract-pane::before { opacity: 0.35; mix-blend-mode: soft-light; }

        .form-content { position: relative; z-index: 10; }
        .step-container { display: none; animation: fadeSlideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .step-container.active { display: block; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.5); border-radius: 3px; }

        input[type=file]::file-selector-button {
            border: 1px solid rgba(100, 116, 139, 0.3); padding: 0.5rem 1rem; border-radius: 0.375rem; background-color: transparent;
            font-family: 'Inter', sans-serif; font-weight: 500; color: inherit; cursor: pointer; transition: all 0.2s; margin-right: 1rem;
        }
        input[type=file]::file-selector-button:hover { background-color: rgba(100, 116, 139, 0.1); }
        
        select option { font-family: 'Inter', sans-serif; font-size: 1rem; }
    </style>
</head>
<body class="text-slate-800 dark:text-slate-300 antialiased min-h-screen relative font-sans">

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

    <header class="fixed top-0 w-full z-50 p-5 flex justify-between items-center bg-parchment-100/60 dark:bg-slate-900/60 backdrop-blur-md border-b border-slate-300/30 dark:border-slate-700/50 transition-colors">
        <div class="flex items-center gap-3 relative z-10">
            <img src="Logo/Rex_logo.png" alt="REX Logo" class="h-10 w-auto" onerror="this.style.display='none'">
            <div class="flex items-center font-serif text-xl tracking-wide text-slate-900 dark:text-white drop-shadow-sm whitespace-nowrap">
                <span class="font-black text-2xl tracking-[0.1em] bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600 dark:from-slate-100 dark:to-slate-400">REX&reg; - </span> 
                <span class="hidden md:inline font-semibold opacity-90 ml-2">Residential Property Exchange</span>
            </div>
        </div>
        
        <div class="absolute left-1/2 transform -translate-x-1/2 hidden lg:block text-center z-0">
            <h1 class="font-serif font-bold text-2xl tracking-widest uppercase text-slate-900 dark:text-white underline underline-offset-8 decoration-2 decoration-slate-400/60">
                Home Equity Questionnaire
            </h1>
        </div>

        <div class="flex items-center gap-6 relative z-10">
            <span class="text-sm tracking-widest uppercase font-bold opacity-80" id="progress-text">Document 1 / <?php echo count($questionnaire); ?></span>
            <button onclick="toggleDarkMode()" class="p-2.5 rounded-full hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-colors" title="Toggle Theme">
                <svg id="icon-sun" class="w-6 h-6 hidden dark:block text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="icon-moon" class="w-6 h-6 block dark:hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
        </div>
    </header>

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 min-h-screen flex items-center justify-center">
        
        <?php if ($submitted): ?>
            <div class="contract-pane rounded-sm max-w-4xl w-full p-12 sm:p-16 text-center overflow-hidden relative border border-slate-300 dark:border-slate-700">
                <div class="contract-inner-border"></div>
                <div class="form-content">
                    <div class="w-20 h-20 border-2 border-slate-800 dark:border-slate-200 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="w-10 h-10 text-slate-800 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-4xl font-serif font-bold mb-6">Submission Registered</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-10 leading-relaxed font-light">
                        The quantitative metrics and official documentation regarding your asset have been securely logged into our system. Our valuation engine will now cross-reference this data to establish precise equity framing.
                    </p>
                    <a href="?" class="inline-block bg-slate-800 hover:bg-slate-700 dark:bg-slate-200 dark:hover:bg-white text-white dark:text-slate-900 font-semibold py-4 px-10 rounded shadow transition-all text-lg">Acknowledge & Return</a>
                </div>
            </div>
        <?php else: ?>

            <form id="valuation-form" novalidate method="POST" enctype="multipart/form-data" class="contract-pane rounded-sm max-w-4xl w-full border border-slate-300 dark:border-slate-700 overflow-hidden relative flex flex-col">
                <div class="contract-inner-border"></div>
                
                <div class="w-full bg-slate-200/50 dark:bg-slate-800/50 h-1.5 absolute top-0 left-0 z-20">
                    <div id="progress-bar" class="bg-slate-600 dark:bg-slate-300 h-1.5 transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>

                <div class="form-content flex-grow p-10 sm:p-14">
                    <?php foreach ($questionnaire as $index => $stepData): ?>
                        <div class="step-container <?php echo $index === 0 ? 'active' : ''; ?>" data-step="<?php echo $index; ?>">
                            
                            <div class="mb-12 text-center sm:text-left">
                                <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white mb-3"><?php echo $stepData['title']; ?></h2>
                                <?php if(isset($stepData['subtitle'])): ?>
                                    <p class="text-base font-medium tracking-wide uppercase text-slate-500 dark:text-slate-400 mb-8"><?php echo $stepData['subtitle']; ?></p>
                                <?php endif; ?>

                                <?php if (isset($stepData['is_disclaimer']) && $stepData['is_disclaimer']): ?>
                                    <div class="text-left text-slate-700 dark:text-slate-300 font-serif leading-relaxed text-xl border-y border-slate-300/50 dark:border-slate-700/50 py-8 my-8">
                                        <?php echo $stepData['content']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($stepData['is_personal']) && $stepData['is_personal']): ?>
                                    <div class="bg-emerald-50 dark:bg-emerald-900/10 border-l-4 border-emerald-600 p-6 mt-6 text-left shadow-sm">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-base text-emerald-900 dark:text-emerald-200 font-serif">
                                                    <strong>Personal & Secure Information.</strong> We will NOT share this information publicly. It is strictly used to deliver your report, verify compliance, and prevent fraud.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif (!isset($stepData['is_disclaimer'])): ?>
                                    <div class="bg-orange-50 dark:bg-orange-950/30 border-l-4 border-orange-600 p-5 mt-6 text-left shadow-sm">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-base text-orange-900 dark:text-orange-200 font-serif">
                                                    <strong>Public Data Notice.</strong> Please be aware that the information provided in this section will be made publicly available. This transparency helps us maintain accurate community market pricing.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="grid grid-cols-1 <?php echo isset($stepData['is_disclaimer']) ? '' : 'md:grid-cols-2'; ?> gap-x-8 gap-y-10">
                                <?php foreach ($stepData['questions'] as $q): ?>
                                    <?php 
                                        $isRequired = isset($q['required']) && $q['required'] ? 'required' : '';
                                        $fullWidth = ($q['type'] === 'checkbox' || $q['type'] === 'file' || $q['name'] === 'contact_email' || $q['name'] === 'street_address') ? 'col-span-1 md:col-span-2' : '';
                                    ?>
                                    
                                    <div class="flex flex-col <?php echo $fullWidth; ?> relative">
                                        <?php if ($q['type'] !== 'checkbox'): ?>
                                            <label for="<?php echo $q['name']; ?>" class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200">
                                                <?php echo $q['label']; ?> <?php echo $isRequired ? '<span class="text-emerald-600 dark:text-emerald-400 ml-1">*</span>' : ''; ?>
                                            </label>
                                        <?php endif; ?>

                                        <?php if ($q['type'] === 'checkbox'): ?>
                                            <label class="flex items-center space-x-5 cursor-pointer p-6 bg-white/50 dark:bg-slate-800/40 rounded border border-slate-300 dark:border-slate-600 hover:bg-white dark:hover:bg-slate-800 transition-colors">
                                                <input type="checkbox" name="<?php echo $q['name']; ?>" id="<?php echo $q['name']; ?>" <?php echo $isRequired; ?>
                                                    class="w-6 h-6 text-slate-800 bg-transparent border-slate-400 rounded focus:ring-slate-500 focus:ring-2 dark:border-slate-500">
                                                <span class="text-base font-medium text-slate-800 dark:text-slate-200 leading-relaxed">
                                                    <?php echo $q['label']; ?> <?php echo $isRequired ? '<span class="text-emerald-600 dark:text-emerald-400">*</span>' : ''; ?>
                                                </span>
                                            </label>

                                        <?php elseif ($q['type'] === 'file'): ?>
                                            <input type="file" name="<?php echo $q['name']; ?><?php echo isset($q['multiple']) ? '[]' : ''; ?>" id="<?php echo $q['name']; ?>" 
                                                accept="<?php echo isset($q['accept']) ? $q['accept'] : '*/*'; ?>"
                                                <?php echo isset($q['multiple']) && $q['multiple'] ? 'multiple' : ''; ?>
                                                <?php echo $isRequired; ?>
                                                class="w-full text-base font-handwriting text-2xl tracking-wide text-slate-700 dark:text-slate-300 p-3 bg-white/40 dark:bg-slate-800/40 border border-slate-300 dark:border-slate-600 rounded focus:outline-none">
                                            <p class="text-sm text-slate-500 mt-2">Accepted formats: <?php echo isset($q['accept']) ? $q['accept'] : 'All files'; ?></p>

                                        <?php elseif ($q['type'] === 'select'): ?>
                                            <select name="<?php echo $q['name']; ?>" id="<?php echo $q['name']; ?>" <?php echo $isRequired; ?>
                                                class="w-full font-handwriting text-3xl tracking-wide bg-transparent border-b-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white px-2 py-2 focus:border-slate-800 dark:focus:border-slate-300 outline-none transition-colors cursor-pointer">
                                                <option value="" disabled selected class="text-slate-400 font-sans text-base"><?php echo isset($q['placeholder']) ? $q['placeholder'] : 'Select an option...'; ?></option>
                                                <?php foreach($q['options'] as $opt): ?>
                                                    <option value="<?php echo $opt; ?>" class="bg-parchment-100 dark:bg-slate-800 text-slate-900 dark:text-white font-sans text-base"><?php echo $opt; ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        <?php else: ?>
                                            <input type="<?php echo $q['type']; ?>" name="<?php echo $q['name']; ?>" id="<?php echo $q['name']; ?>" 
                                                placeholder="<?php echo isset($q['placeholder']) ? $q['placeholder'] : ''; ?>" 
                                                value="<?php echo isset($q['defaultValue']) ? $q['defaultValue'] : ''; ?>"
                                                <?php echo isset($q['step']) ? 'step="'.$q['step'].'"' : ''; ?> 
                                                <?php echo isset($q['max']) ? 'max="'.$q['max'].'"' : ''; ?> 
                                                <?php echo $isRequired; ?>
                                                class="w-full font-handwriting text-3xl tracking-wide bg-transparent border-b-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white px-2 py-2 focus:border-slate-800 dark:focus:border-slate-300 outline-none transition-colors">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="relative border-t border-slate-300/60 dark:border-slate-700/60 px-10 py-8 flex justify-between items-center z-10 bg-parchment-100/90 dark:bg-slate-900/90">
                    <button type="button" id="btn-prev" onclick="changeStep(-1)" class="hidden text-base uppercase tracking-wider font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                        ← Previous
                    </button>
                    
                    <div class="ml-auto flex items-center gap-4">
                        <button type="button" id="btn-next" onclick="changeStep(1)" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-200 dark:hover:bg-white text-white dark:text-slate-900 px-10 py-4 rounded shadow font-bold tracking-wide transition-all text-lg">
                            Proceed to Next
                        </button>
                        <button type="submit" id="btn-submit" class="hidden bg-emerald-700 hover:bg-emerald-600 text-white px-10 py-4 rounded shadow font-bold tracking-wide transition-all text-lg">
                            Submit Documentation
                        </button>
                    </div>
                </div>

            </form>
        <?php endif; ?>
    </main>

    <script>
        // Theme Toggle Logic
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Dynamic Freehold/Leasehold Logic
        document.addEventListener('DOMContentLoaded', () => {
            const tenureSelect = document.getElementById('tenure_type');
            if (tenureSelect) {
                tenureSelect.addEventListener('change', function() {
                    const leaseholdInput = document.getElementById('leasehold_years');
                    const leaseholdContainer = leaseholdInput.closest('.flex-col');
                    
                    if(this.value.includes('Freehold')) {
                        // Hide and clear if Freehold
                        leaseholdContainer.style.display = 'none';
                        leaseholdInput.value = '';
                        leaseholdInput.removeAttribute('required');
                    } else {
                        // Show and require if Leasehold
                        leaseholdContainer.style.display = 'flex';
                        leaseholdInput.setAttribute('required', 'true');
                    }
                });
            }
        });

        // Intercept Enter Key to prevent native submission/popups and map it to Next
        document.getElementById('valuation-form').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName.toLowerCase() !== 'textarea') {
                e.preventDefault(); 
                if (currentStep < totalSteps - 1) {
                    changeStep(1);
                } else {
                    document.getElementById('btn-submit').click();
                }
            }
        });

        // Stepper Logic
        const totalSteps = <?php echo isset($questionnaire) ? count($questionnaire) : 0; ?>;
        let currentStep = 0;

        function updateUI() {
            document.querySelectorAll('.step-container').forEach((el, index) => {
                if(index === currentStep) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });

            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');

            if (currentStep === 0) {
                btnPrev.classList.add('hidden');
            } else {
                btnPrev.classList.remove('hidden');
            }

            if (currentStep === totalSteps - 1) {
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
            } else {
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
            }

            const progressPercent = ((currentStep + 1) / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = `${progressPercent}%`;
            document.getElementById('progress-text').innerText = `Document ${currentStep + 1} / ${totalSteps}`;
            
            if(window.innerWidth < 640) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function validateCurrentStep() {
            const currentContainer = document.querySelector(`.step-container[data-step="${currentStep}"]`);
            const allInputs = currentContainer.querySelectorAll('input, select');
            let isValid = true;

            allInputs.forEach(input => {
                // Skip validation if the parent is hidden (e.g., Leasehold field when Freehold is selected)
                if (input.closest('.flex-col').style.display === 'none') {
                    return; 
                }

                let isInvalid = false;
                let errorMessage = "";
                
                // Native HTML5 validation (catches type mismatch, max, min, required)
                if (!input.checkValidity()) {
                    isInvalid = true;
                    errorMessage = input.validationMessage || "Invalid input type.";
                }

                // Fallback custom validation for required emptiness
                if (input.hasAttribute('required') && !isInvalid) {
                    if (input.type === 'checkbox' && !input.checked) {
                        isInvalid = true;
                        errorMessage = "You must acknowledge this to proceed.";
                    } else if (input.type === 'file' && input.files.length === 0) {
                        isInvalid = true;
                        errorMessage = "Please upload a required document.";
                    } else if (input.tagName.toLowerCase() === 'select' && !input.value) {
                        isInvalid = true;
                        errorMessage = "Please select an option from the list.";
                    } else if (!input.value.trim()) {
                        isInvalid = true;
                        errorMessage = "This field cannot be left blank.";
                    }
                }

                const parentCol = input.closest('.flex-col');
                const existingError = parentCol.querySelector('.validation-error');
                if (existingError) existingError.remove();

                if (isInvalid) {
                    isValid = false;
                    
                    const errorEl = document.createElement('span');
                    errorEl.className = 'validation-error text-red-600 dark:text-red-400 text-sm font-bold mt-2 font-sans tracking-wide';
                    errorEl.innerText = '⚠ ' + errorMessage;

                    if(input.type === 'checkbox') {
                        input.parentElement.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                        parentCol.appendChild(errorEl);
                        input.addEventListener('change', function() {
                            this.parentElement.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                            const err = parentCol.querySelector('.validation-error');
                            if(err) err.remove();
                        }, {once: true});
                    } else {
                        input.classList.add('border-red-500');
                        parentCol.appendChild(errorEl);
                        
                        const removeError = function() {
                            this.classList.remove('border-red-500');
                            const err = parentCol.querySelector('.validation-error');
                            if(err) err.remove();
                        };
                        
                        input.addEventListener('input', removeError, {once: true});
                        input.addEventListener('change', removeError, {once: true});
                    }
                }
            });

            return isValid;
        }

        function changeStep(direction) {
            if (direction === 1 && !validateCurrentStep()) return; 
            currentStep += direction;
            updateUI();
        }

        if(totalSteps > 0) updateUI();

        // Handle the actual form submission to prevent silent HTML5 blocks and show loading
        document.getElementById('valuation-form').addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault(); 
                return;
            }
            
            const submitBtn = document.getElementById('btn-submit');
            submitBtn.innerHTML = 'Uploading Data... ⏳';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            submitBtn.style.pointerEvents = 'none';
        });
    </script>
</body>
</html>