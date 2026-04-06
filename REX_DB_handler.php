<?php
// Default XAMPP Credentials
$host = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "rex_database";

// 1. Connect to MySQL server
$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 2. Create the Database
$create_db_query = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($create_db_query) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// ========================================================================
// 3. USERS TABLE (Generic for ALL users: Investors & Homeowners)
// ========================================================================
$create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    legal_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) DEFAULT NULL, -- Nullable for users who haven't set up full login yet
    phone_number VARCHAR(50) DEFAULT NULL,
    tax_id_number VARCHAR(100) DEFAULT NULL,
    country_of_residence VARCHAR(100) DEFAULT NULL,
    
    role ENUM('investor', 'homeowner', 'admin') NOT NULL DEFAULT 'investor',
    kyc_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($create_users) === FALSE) {
    die("Error creating users table: " . $conn->error);
}

// ========================================================================
// 4. PROPERTY PUBLIC MARKET TABLE (Safe to broadcast to terminal)
// ========================================================================
$create_public_properties = "CREATE TABLE IF NOT EXISTS property_public_market (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    owner_id INT(11) NOT NULL, 
    
    city VARCHAR(100) NOT NULL,
    state_region VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    
    property_type VARCHAR(100) NOT NULL,
    zoning_class VARCHAR(100) DEFAULT NULL,
    tenure_type VARCHAR(100) NOT NULL,
    leasehold_years INT(4) DEFAULT NULL,
    
    official_living_area DECIMAL(10,2) NOT NULL,
    official_lot_size DECIMAL(10,2) NOT NULL,
    year_built INT(4) NOT NULL,
    last_renovation_year INT(4) DEFAULT NULL,
    total_bedrooms TINYINT(3) NOT NULL,
    wet_rooms TINYINT(3) NOT NULL,
    roof_material VARCHAR(100) DEFAULT NULL,
    roof_age INT(4) DEFAULT NULL,
    hvac_type VARCHAR(100) DEFAULT NULL,
    hvac_age INT(4) DEFAULT NULL,
    parking_spaces TINYINT(3) DEFAULT NULL,
    has_pool VARCHAR(50) DEFAULT NULL,
    waterfront_access VARCHAR(50) DEFAULT NULL,

    outstanding_debt DECIMAL(15,2) NOT NULL,
    annual_tax DECIMAL(12,2) DEFAULT NULL,
    annual_insurance DECIMAL(12,2) DEFAULT NULL,
    monthly_maintenance DECIMAL(12,2) DEFAULT NULL,
    rental_income DECIMAL(12,2) DEFAULT NULL,
    
    legal_entity_type VARCHAR(100) DEFAULT NULL,
    has_active_liens VARCHAR(50) DEFAULT NULL,
    has_easements VARCHAR(50) DEFAULT NULL,
    has_pending_litigation VARCHAR(50) DEFAULT NULL,
    has_rental_restrictions VARCHAR(50) DEFAULT NULL,
    energy_rating VARCHAR(50) DEFAULT NULL,
    climate_risk_zone VARCHAR(100) DEFAULT NULL,
    has_solar_panels VARCHAR(50) DEFAULT NULL,
    has_environmental_hazards VARCHAR(50) DEFAULT NULL,
    
    equity_target DECIMAL(5,2) NOT NULL,
    listing_status ENUM('pending_audit', 'underwritten', 'active_market', 'delisted') NOT NULL DEFAULT 'pending_audit',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($create_public_properties) === FALSE) {
    die("Error creating public properties table: " . $conn->error);
}

// ========================================================================
// 5. PROPERTY PRIVATE COMPLIANCE TABLE (Strictly confidential)
// ========================================================================
$create_private_properties = "CREATE TABLE IF NOT EXISTS property_private_compliance (
    property_id INT(11) PRIMARY KEY, 
    
    street_address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(50) NOT NULL,
    
    consent_disclaimer TINYINT(1) NOT NULL,
    ownership_doc LONGBLOB NOT NULL,
    
    FOREIGN KEY (property_id) REFERENCES property_public_market(id) ON DELETE CASCADE
)";

if ($conn->query($create_private_properties) === FALSE) {
    die("Error creating private compliance table: " . $conn->error);
}

// ========================================================================
// 6. DATABASE CLEANUP (Drop all unlisted tables)
// ========================================================================

// Define the strict list of tables that are allowed to exist
$allowed_tables = [
    'users', 
    'property_public_market', 
    'property_private_compliance'
];

// Fetch all tables currently in the database
$result = $conn->query("SHOW TABLES");

if ($result) {
    // Disable foreign key checks so we can drop tables without constraint errors
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    while ($row = $result->fetch_array()) {
        $table_name = $row[0];
        
        // If the table is NOT in our allowed list, incinerate it
        if (!in_array($table_name, $allowed_tables)) {
            $drop_query = "DROP TABLE IF EXISTS `$table_name`";
            $conn->query($drop_query);
        }
    }

    // Re-enable foreign key checks to protect our new relational architecture
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
}

?>