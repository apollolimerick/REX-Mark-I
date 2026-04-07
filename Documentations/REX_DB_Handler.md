# Database Setup & Enforcement Script

## Overview
This script manages the automated initialization and strict state enforcement of the `rex_database` MySQL environment. It is designed to bootstrap the core application schema, physically separate public marketplace data from sensitive compliance records, and enforce database integrity by incinerating unauthorized tables.

## Core Functionalities

* **Automated Bootstrapping:** Connects to the local MySQL server (configured for default XAMPP credentials) and creates the `rex_database` if it does not already exist.
* **Relational Schema Deployment:** Establishes the three foundational tables required for the platform, enforcing relationships using Foreign Keys and `ON DELETE CASCADE` rules.
* **Strict State Enforcement:** Includes a self-cleaning mechanism that queries the database for all existing tables. It temporarily disables foreign key checks to forcefully drop any table that is not explicitly defined in the `$allowed_tables` array, ensuring a clean and predictable database state.

## Database Schema Architecture

The script enforces a strict three-table architecture designed with data privacy in mind:

| Table Name | Purpose | Relational Mapping |
| :--- | :--- | :--- |
| **`users`** | Core authentication and identity table. Manages roles (investor, homeowner, admin) and KYC compliance status. | Primary Key: `id` |
| **`property_public_market`** | The public-facing marketplace ledger. Stores structural property metrics, financials, and zoning data safe for broad distribution. | Foreign Key: `owner_id` references `users(id)` |
| **`property_private_compliance`** | A highly restricted table holding sensitive Personally Identifiable Information (PII), exact street addresses, and binary ownership documents. | Foreign Key: `property_id` references `property_public_market(id)` |

## Security & Architecture Notes

* **Data Separation:** By splitting property data into `public_market` and `private_compliance`, the application prevents accidental exposure of sensitive homeowner information to front-end clients or unauthorized API endpoints.
* **Credential Management:** Currently, the script uses hardcoded default XAMPP credentials (`root` / `""`). **Do not deploy this file to production without migrating to secure, environment-variable-based credential injection.**
* **Destructive Cleanup:** The cleanup routine at the end of the file is highly destructive by design. It will permanently delete any table not listed in the `$allowed_tables` array. Use caution when running this script in environments where other services might share the database.
