# REX Home Equity Underwriting Questionnaire

## Overview
This file serves as the intake gateway for homeowners looking to underwrite and tokenize their real estate on the REX platform. It presents a heavily stylized, multi-step "Glassmorphism/Parchment" UI form that securely captures personal identity, structural property metrics, financial risk factors, and compliance documentation.

## Core Functionality

### 1. Multi-Step Form Engine
The UI is broken down into seven distinct, progressive sections:
1.  **Compliance & Mutual Security:** Legal disclaimers and authorization checkboxes.
2.  **Private Identity & Location:** PII (Personally Identifiable Information) collection.
3.  **Public Market Classification:** Property type, zoning, and tenure (Freehold vs. Leasehold).
4.  **Structural Attributes:** Square footage, age, bedrooms, and HVAC systems.
5.  **Financial & Yield Metrics:** Outstanding debt, taxes, and rental income.
6.  **Risk & ESG Reporting:** Environmental hazards, pending litigation, and climate risk.
7.  **Tokenization & Documents:** Target equity percentage and file uploads for deeds/ownership records.

The form uses JavaScript to handle seamless transitions between steps (`changeStep()` and `updateUI()`), complete with an animated progress bar.

### 2. Client-Side Validation
Before allowing a user to proceed to the next step, a custom `validateCurrentStep()` JavaScript function executes. It overrides default HTML5 popups, providing inline, styled error messages (e.g., "⚠ This field cannot be left blank") and red highlighting for any missed required fields, ensuring a smooth user experience without premature server requests. It also dynamically enforces requirements; for example, if "Leasehold" is selected, the "Years Remaining" input becomes mandatory.

### 3. Server-Side Processing & Database Insertion
Upon final submission, PHP processes the `$_POST` array and handles file uploads (`$_FILES`).

**Transaction Safety:**
The script utilizes a `$conn->begin_transaction()` block to securely map the incoming data across three distinct tables:
1.  **`users`:** Verifies if the email exists; if not, provisions a new `homeowner` profile.
2.  **`property_public_market`:** Inserts 36 mapped parameters (using `bind_param`) for public-facing metrics.
3.  **`property_private_compliance`:** Inserts sensitive data (exact street address) and stores the uploaded PDF/Image deed as a binary `BLOB` using `$stmt->send_long_data()`.

If any query fails or an upload error occurs, a `$conn->rollback()` is triggered, preventing orphaned records or corrupted data states.

## UI/UX Design Stack

* **Styling:** Driven by a custom **Tailwind CSS** configuration.
* **Aesthetics:** The form utilizes a hybrid design, combining modern dark mode "glass" elements with a textured, noise-filtered "parchment" effect for the central contract pane, evoking the feeling of a formalized legal document.
* **Typography:** Leverages Google Fonts (`Inter` for data, `Playfair Display` for headers, and `Caveat` for a handwriting effect on user input fields).
