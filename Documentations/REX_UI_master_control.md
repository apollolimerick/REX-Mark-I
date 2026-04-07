# REX Master Data Control Terminal

## Overview
The **REX Master Data Control Terminal** is an administrative dashboard and robust data-seeding engine designed to populate the REX platform's relational database with high volumes of mock data. It enables developers and administrators to rapidly generate interconnected users, public marketplace assets, and private compliance documents while strictly maintaining relational integrity across the architecture.

## Core Functionalities

* **Master Relational Seeding:** Processes batch insertions for three distinct layers of the platform simultaneously:
  * **Users:** Generates mock investors and homeowners with localized geographic data, randomized KYC statuses, and secure hashed passwords.
  * **Public Assets:** Populates the `property_public_market` table with realistic structural metrics, valuations, and debt ratios, intelligently assigning them to newly generated (or existing) homeowner profiles.
  * **Private Compliance Documents:** Generates mock binary ownership documents (BLOBs) and sensitive PII, strictly mapping them 1-to-1 with public assets that do not yet have compliance records.
* **Database Architecture Monitoring:** Real-time UI badges display the exact row counts for the three foundational tables (`users`, `property_public_market`, `property_private_compliance`).
* **Blockchain Ledger Synthesis:** A placeholder utility prepared to compile existing relational properties and roles into an immutable, verifiable `.txt` hash log simulating on-chain events.

## Technical Implementation & Safety Constraints

The seeder is built to simulate a production-grade environment, utilizing several safety mechanisms to prevent data corruption during massive injection events:

| Mechanism | Description |
| :--- | :--- |
| **MySQL Transactions** | All seeding operations are wrapped in a `$conn->begin_transaction()`. If any query fails (e.g., a foreign key constraint violation), the entire batch is rolled back to prevent orphaned records. |
| **Prepared Statements** | Uses `bind_param` and `send_long_data` (for binary blobs) to execute insertions securely, mitigating SQL injection risks even when handling randomized internal data. |
| **Relational Failsafes** | If public assets are generated but no homeowners exist in the database, the script automatically generates a "System Genesis Owner" to satisfy foreign key constraints. |
| **Strict 1-to-1 Capping** | The private compliance generation query specifically filters out public assets that already have a private document (`NOT IN (SELECT property_id...)`), preventing duplication and constraint errors. |

## Data Generation Dictionaries

To ensure the mock database looks realistic for UI/UX testing, the script relies on seeded data arrays:
* **Global Geographies:** Maps specific cities to their correct states, countries, and continents (e.g., Tokyo &rarr; Japan &rarr; Asia).
* **Property Archetypes:** Randomizes between Single-Family, Condos, Townhouses, and Mixed-Use buildings.
* **Tenure Types:** Distributes ownership models between Freehold (Fee Simple) and Leasehold. 

## UI/UX Design

The terminal features a "Glassmorphism" interface overlaying a blurred, dynamic grid of property images. It provides administrators with precise input controls to define the exact number of rows to inject per table (up to 5,000 per batch) and returns immediate, color-coded system feedback (Success, Info, or Error) upon execution.
