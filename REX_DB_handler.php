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


// 4. Create Questionnaire/Property Evaluations Table
$create_evaluations = "CREATE TABLE IF NOT EXISTS property_data (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    
    -- Step 1: Compliance
    consent_disclaimer TINYINT(1) NOT NULL,
    
    -- Step 2: Official Registry
    cadastral_id VARCHAR(255) NOT NULL,
    official_lot_size DECIMAL(10,2) NOT NULL,
    official_living_area DECIMAL(10,2) NOT NULL,
    year_built INT(4) NOT NULL,
    last_transfer_year INT(4) NOT NULL,
    
    -- Step 3: Quantitative Specs
    ceiling_height DECIMAL(5,2) NOT NULL,
    total_rooms INT(11) NOT NULL,
    wet_rooms INT(11) NOT NULL,
    outbuildings INT(11) DEFAULT 0,
    garage_sqm DECIMAL(10,2),
    wall_thickness INT(11) NOT NULL,
    
    -- Step 4: Geospatial & Financial
    dist_transit INT(11) NOT NULL,
    dist_center DECIMAL(10,2) NOT NULL,
    annual_tax DECIMAL(12,2) NOT NULL,
    monthly_maintenance DECIMAL(10,2) NOT NULL,
    
    -- Step 5: Verification (Storing file paths)
    legal_name VARCHAR(255) NOT NULL,
    contact_email VARCHAR(255) NOT NULL,
    ownership_doc VARCHAR(255) NOT NULL,
    property_photos TEXT, 
    
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// 5. NEW: Create Users Table
$create_users = "CREATE TABLE IF NOT EXISTS users_data (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(50),
    tax_id_number VARCHAR(100) NOT NULL, -- SSN, TIN, or National Tax ID
    country_of_residence VARCHAR(100),
    kyc_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";


if ($conn->query($create_evaluations) === FALSE) {
    die("Error creating evaluations table: " . $conn->error);
}


if ($conn->query($create_users) === FALSE) {
    die("Error creating evaluations table: " . $conn->error);
}
?>