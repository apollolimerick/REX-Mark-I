<?php
// Handle form submission and file uploads
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real application, process $_POST and $_FILES here.
    // Example: move_uploaded_file($_FILES['ownership_doc']['tmp_name'], $destination);
    $submitted = true;
}

// Define the new, quantitative-focused questionnaire
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
        "title" => "Official Registry Data",
        "subtitle" => "Please refer to your official property deed or cadastral extract.",
        "questions" => [
            ["name" => "cadastral_id", "label" => "Cadastral / Parcel Reference Number", "type" => "text"],
            ["name" => "official_lot_size", "label" => "Registered Plot Size (m²)", "type" => "number", "step" => "0.1"],
            ["name" => "official_living_area", "label" => "Registered Living Area (m²)", "type" => "number", "step" => "0.1"],
            ["name" => "year_built", "label" => "Official Year of Construction", "type" => "number", "placeholder" => "YYYY"],
            ["name" => "last_transfer_year", "label" => "Year of Last Registered Transfer/Sale", "type" => "number", "placeholder" => "YYYY"],
        ]
    ],
    [
        "step" => 3,
        "title" => "Quantitative Specifications",
        "subtitle" => "Exact physical measurements of the structure.",
        "questions" => [
            ["name" => "ceiling_height", "label" => "Average Ceiling Height (meters)", "type" => "number", "step" => "0.01", "placeholder" => "e.g., 2.75"],
            ["name" => "total_rooms", "label" => "Total Count of Enclosed Rooms", "type" => "number"],
            ["name" => "wet_rooms", "label" => "Count of Wet Rooms (Bathrooms/Toilets)", "type" => "number"],
            ["name" => "outbuildings", "label" => "Number of Registered Outbuildings", "type" => "number", "defaultValue" => "0"],
            ["name" => "garage_sqm", "label" => "Garage Area Size (m²)", "type" => "number", "step" => "0.1", "placeholder" => "Leave blank if none"],
            ["name" => "wall_thickness", "label" => "Primary Exterior Wall Thickness (cm)", "type" => "number", "step" => "1"],
        ]
    ],
    [
        "step" => 4,
        "title" => "Geospatial & Financial Metrics",
        "subtitle" => "Location and ongoing fiscal obligations.",
        "questions" => [
            ["name" => "dist_transit", "label" => "Linear Distance to Transit Station (meters)", "type" => "number"],
            ["name" => "dist_center", "label" => "Linear Distance to City Center (km)", "type" => "number", "step" => "0.1"],
            ["name" => "annual_tax", "label" => "Annual Municipal Property Tax ($/€)", "type" => "number"],
            ["name" => "monthly_maintenance", "label" => "Average Monthly Maintenance/HOA ($/€)", "type" => "number"],
        ]
    ],
    [
        "step" => 5,
        "title" => "Verification & Ownership",
        "subtitle" => "Upload requested documents for compliance review.",
        "is_personal" => true,
        "questions" => [
            ["name" => "legal_name", "label" => "Full Legal Name (as on deed)", "type" => "text"],
            ["name" => "contact_email", "label" => "Secure Contact Email", "type" => "email"],
            ["name" => "ownership_doc", "label" => "Upload Official Ownership Paper / Deed (Must contain your name)", "type" => "file", "accept" => ".pdf,.jpg,.jpeg,.png", "required" => true],
            ["name" => "property_photos", "label" => "Upload Property Photos (Exterior & Interior)", "type" => "file", "accept" => "image/*", "multiple" => true],
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
                        slate: {
                            850: '#151e2e',
                            900: '#0f172a',
                        },
                        parchment: {
                            100: '#f9f8f3',
                            200: '#f0ebd8',
                        }
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
        body {
            transition: background-color 0.4s, color 0.4s;
            background-color: #e2e8f0; /* Base background */
            font-size: 1.05rem; /* Raised base font size slightly */
        }
        .dark body { background-color: #0c0a09; } /* Darker, warmer background to match parchment */

        /* Background Canvas with visible but softened houses */
        .bg-canvas {
            position: fixed;
            top: -20vh; left: -20vw; width: 140vw; height: 140vh; /* Enlarged bounds to prevent empty corners on heavy tilts */
            z-index: -2;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #050505; /* Darker backdrop for the Netflix feel */
        }

        .netflix-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr); /* 6 columns staggers the 11 images dynamically row by row */
            gap: 16px;
            transform: rotate(-18deg) scale(1.6); /* Tilted several degrees further */
            width: 160vw;
            filter: blur(3px); /* Slight blur */
            opacity: 0.6;
            transition: opacity 0.5s, filter 0.5s;
        }

        .dark .netflix-grid {
            opacity: 0.3;
            filter: blur(4px);
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

        /* Halos and Echos of Light */
        .light-halo {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120vw; height: 120vh;
            background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 60%);
            z-index: -1;
            pointer-events: none;
        }
        
        .dark .light-halo {
            background: radial-gradient(circle, rgba(210, 180, 140, 0.05) 0%, rgba(12, 10, 9, 0) 70%); /* Warmer halo */
        }

        /* Old Testament / Parchment Pane Styling */
        .contract-pane {
            position: relative;
            background-color: var(--paper-bg, #f9f8f3);
            /* Glowing echo on the pane itself */
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.2), 0 0 40px rgba(255,255,255,0.4);
        }

        .dark .contract-pane {
            --paper-bg: #27231f; /* Dark sepia/parchment tone */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), 0 0 60px -15px rgba(210, 180, 140, 0.05);
        }

        /* Etched double border for traditional feel */
        .contract-inner-border {
            position: absolute;
            top: 8px; bottom: 8px; left: 8px; right: 8px;
            border: 1px solid rgba(100, 116, 139, 0.3);
            outline: 1px solid rgba(100, 116, 139, 0.15);
            outline-offset: -4px;
            pointer-events: none;
            z-index: 1;
        }

        /* Stronger Paper Noise Texture */
        .contract-pane::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none;
            opacity: 0.6;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='1.2' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            z-index: 0;
            mix-blend-mode: multiply;
        }

        .dark .contract-pane::before {
            opacity: 0.35; /* Increased opacity for dark mode parchment */
            mix-blend-mode: soft-light; /* Changed blend mode to keep texture visible on dark */
        }

        .form-content { position: relative; z-index: 10; }
        .step-container { display: none; animation: fadeSlideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .step-container.active { display: block; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom minimal scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.5); border-radius: 3px; }

        /* File input styling */
        input[type=file]::file-selector-button {
            border: 1px solid rgba(100, 116, 139, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background-color: transparent;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: inherit;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 1rem;
        }
        input[type=file]::file-selector-button:hover {
            background-color: rgba(100, 116, 139, 0.1);
        }
    </style>
</head>
<body class="text-slate-800 dark:text-slate-300 antialiased min-h-screen relative font-sans">

    <!-- Subtle Halo Lighting -->
    <div class="light-halo"></div>

    <!-- Background Images -->
    <div class="bg-canvas">
        <div class="netflix-grid">
            <?php 
                // Core 11 house images
                $houses = [];
                for ($i = 1; $i <= 11; $i++) {
                    $houses[] = "Background_images/house$i.jpg";
                }
                // Merge them multiple times to ensure the steeper tilted grid completely fills any screen size
                // 11 images * 6 repetitions = 66 total squares rendered to safely cover massive desktop screens
                $grid_images = array_merge($houses, $houses, $houses, $houses, $houses, $houses);
                
                // Output the grid items
                foreach ($grid_images as $img): 
            ?>
            <div class="netflix-img" style="background-image: url('<?php echo $img; ?>');"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Minimal Header -->
    <header class="fixed top-0 w-full z-50 p-5 flex justify-between items-center bg-parchment-100/60 dark:bg-slate-900/60 backdrop-blur-md border-b border-slate-300/30 dark:border-slate-700/50 transition-colors">
        
        <!-- Left Side: REX Logo and Text -->
        <div class="flex items-center gap-3 relative z-10">
            <img src="Logo/Rex_logo.png" alt="REX Logo" class="h-10 w-auto" onerror="this.style.display='none'">
            <div class="flex items-center font-serif text-xl tracking-wide text-slate-900 dark:text-white drop-shadow-sm whitespace-nowrap">
                <span class="font-black text-2xl tracking-[0.1em] bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600 dark:from-slate-100 dark:to-slate-400">REX&reg; - </span> 
                <span class="hidden md:inline font-semibold opacity-90 ml-2">Residential Property Exchange</span>
            </div>
        </div>
        
        <!-- Center Title (Absolute Centered) -->
        <div class="absolute left-1/2 transform -translate-x-1/2 hidden lg:block text-center z-0">
            <h1 class="font-serif font-bold text-2xl tracking-widest uppercase text-slate-900 dark:text-white underline underline-offset-8 decoration-2 decoration-slate-400/60">
                Home Equity Questionnaire
            </h1>
        </div>

        <!-- Right Side: Navigation & Theme Toggle -->
        <div class="flex items-center gap-6 relative z-10">
            <span class="text-sm tracking-widest uppercase font-bold opacity-80" id="progress-text">Document 1 / <?php echo count($questionnaire); ?></span>
            <button onclick="toggleDarkMode()" class="p-2.5 rounded-full hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-colors" title="Toggle Theme">
                <svg id="icon-sun" class="w-6 h-6 hidden dark:block text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="icon-moon" class="w-6 h-6 block dark:hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 min-h-screen flex items-center justify-center">
        
        <?php if ($submitted): ?>
            <!-- Success Window Enlarged -->
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

            <!-- Input Form Window Enlarged to max-w-4xl -->
            <form id="valuation-form" method="POST" enctype="multipart/form-data" class="contract-pane rounded-sm max-w-4xl w-full border border-slate-300 dark:border-slate-700 overflow-hidden relative flex flex-col">
                <div class="contract-inner-border"></div>
                
                <!-- Subdued Progress Bar -->
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
                                    <!-- Disclaimer Text -->
                                    <div class="text-left text-slate-700 dark:text-slate-300 font-serif leading-relaxed text-xl border-y border-slate-300/50 dark:border-slate-700/50 py-8 my-8">
                                        <?php echo $stepData['content']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($stepData['is_personal']) && $stepData['is_personal']): ?>
                                    <!-- GREEN Themed Personal Privacy Flag -->
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
                                    <!-- Professional Orange-Red Public Flag -->
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

                            <!-- Grid for Questions -->
                            <div class="grid grid-cols-1 <?php echo isset($stepData['is_disclaimer']) ? '' : 'md:grid-cols-2'; ?> gap-x-8 gap-y-10">
                                <?php foreach ($stepData['questions'] as $q): ?>
                                    <?php 
                                        $isRequired = isset($q['required']) && $q['required'] ? 'required' : '';
                                        $fullWidth = ($q['type'] === 'checkbox' || $q['type'] === 'file' || $q['name'] === 'contact_email') ? 'col-span-1 md:col-span-2' : '';
                                    ?>
                                    
                                    <div class="flex flex-col <?php echo $fullWidth; ?>">
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

                                        <?php else: ?>
                                            <!-- Inputs have handwriting font applied via 'font-handwriting text-2xl tracking-wide' -->
                                            <input type="<?php echo $q['type']; ?>" name="<?php echo $q['name']; ?>" id="<?php echo $q['name']; ?>" 
                                                placeholder="<?php echo isset($q['placeholder']) ? $q['placeholder'] : ''; ?>" 
                                                value="<?php echo isset($q['defaultValue']) ? $q['defaultValue'] : ''; ?>"
                                                <?php echo isset($q['step']) ? 'step="'.$q['step'].'"' : ''; ?> 
                                                <?php echo $isRequired; ?>
                                                class="w-full font-handwriting text-3xl tracking-wide bg-transparent border-b-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white px-2 py-2 focus:border-slate-800 dark:focus:border-slate-300 outline-none transition-colors">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                </div>

                <!-- Footer Navigation -->
                <div class="border-t border-slate-300/60 dark:border-slate-700/60 px-10 py-8 flex justify-between items-center z-10 bg-parchment-100/90 dark:bg-slate-900/90">
                    <button type="button" id="btn-prev" onclick="changeStep(-1)" class="hidden text-base uppercase tracking-wider font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                        ← Previous
                    </button>
                    
                    <div class="ml-auto">
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
        // Dark Mode 
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
            const requiredInputs = currentContainer.querySelectorAll('[required]');
            let isValid = true;

            requiredInputs.forEach(input => {
                let isEmpty = false;
                
                if (input.type === 'checkbox' && !input.checked) {
                    isEmpty = true;
                } else if (input.type === 'file' && input.files.length === 0) {
                    isEmpty = true;
                } else if (!input.value.trim()) {
                    isEmpty = true;
                }

                if (isEmpty) {
                    isValid = false;
                    
                    // Style differently based on input type for the "Old Testament" design
                    if(input.type === 'checkbox') {
                        input.parentElement.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                        input.addEventListener('change', function() {
                            this.parentElement.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                        }, {once: true});
                    } else if (input.type === 'file') {
                        input.classList.add('border-red-500');
                        input.addEventListener('change', function() {
                            this.classList.remove('border-red-500');
                        }, {once: true});
                    } else {
                        input.classList.add('border-red-500');
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500');
                        }, {once: true});
                    }
                }
            });

            return isValid;
        }

        function changeStep(direction) {
            if (direction === 1 && !validateCurrentStep()) {
                return; 
            }
            currentStep += direction;
            updateUI();
        }

        if(totalSteps > 0) updateUI();
    </script>
</body>
</html>