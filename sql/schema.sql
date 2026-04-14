-- MAIN CREATION STATEMENTS --
-- Create the database
CREATE DATABASE IF NOT EXISTS plant_db; -- Using an IF NOT EXISTS statement just to prevent recreating if running same code again

USE plant_db; -- If plant_db is not already selected we use this

-- Users table, stores the average user
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Need this to be long, plan to use a hash to store password for increased security
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Simple admin table, distinguishing admins from users
CREATE TABLE IF NOT EXISTS admin (
    user_id INT PRIMARY KEY,
    is_super_admin BOOLEAN DEFAULT FALSE, -- super admins have special privileges to add/delete/modify other admins
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE -- deleting the row in user would also delete the one in admin (no ghost user being pointed to). additionally, we can delete admin without having to worry about it being deleted from the users table (i.e. an admin gets demoted to user)
);


-- Region table to store where the plants are, is likely to be a dropdown option in most cases
CREATE TABLE IF NOT EXISTS region (
    region_id INT AUTO_INCREMENT PRIMARY KEY,
    region_name VARCHAR(50) NOT NULL
);

-- Stores the plants already known about and is in a record
CREATE TABLE IF NOT EXISTS plant (
    plant_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_name VARCHAR(50) NOT NULL,
    species VARCHAR(50),
    category VARCHAR(25)
);

-- user submitted "messy" crowdsourced data:
CREATE TABLE IF NOT EXISTS plant_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    plant_name_suggested VARCHAR(100),
    description TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    submitted_by INT,
    reviewed_by INT,
    region_id INT,
    -- image_path VARCHAR(255), (might make this addition in the future or if there's enough time)
    FOREIGN KEY (submitted_by) REFERENCES users(user_id) ON DELETE SET NULL, -- if a user or admin leaves or doesn't exist, we set the value to NULL here
    FOREIGN KEY (reviewed_by) REFERENCES admin(user_id) ON DELETE SET NULL, 
    FOREIGN KEY (region_id) REFERENCES region(region_id)
);

-- Approved plant reports:
CREATE TABLE IF NOT EXISTS plant_record (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    area_size DECIMAL(10, 2),
    date_added DATE,
    managed_by INT,
    region_id INT,
    FOREIGN KEY (managed_by) REFERENCES admin(user_id) ON DELETE SET NULL, -- same idea as plant records, we set NULL if an admin doesn't exist anymore.
    FOREIGN KEY (region_id) REFERENCES region(region_id)
);

-- Junction table for making sure N to M relationship is followed between plant and plant_records
CREATE TABLE IF NOT EXISTS record_plants (
    record_id INT,
    plant_id INT,
    PRIMARY KEY (record_id, plant_id),
    FOREIGN KEY (record_id) REFERENCES plant_record(record_id) ON DELETE CASCADE,
    FOREIGN KEY (plant_id) REFERENCES plant(plant_id) ON DELETE CASCADE
);

-- Logging Table
CREATE TABLE IF NOT EXISTS audit_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(25),
    action_type ENUM('INSERT', 'UPDATE', 'DELETE'),
    record_id INT,
    admin_id INT, -- Tracks who did it
    log_details VARCHAR(100), -- Brief summary of change
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);