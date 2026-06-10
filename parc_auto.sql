SET FOREIGN_KEY_CHECKS = 0;


CREATE DATABASE IF NOT EXISTS parc_auto
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_romanian_ci;

USE parc_auto;
CREATE TABLE drivers (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name          VARCHAR(60) NOT NULL,
    last_name           VARCHAR(60) NOT NULL,
    phone               VARCHAR(20) NOT NULL,
    email               VARCHAR(100),
    license_category    VARCHAR(20) NOT NULL DEFAULT 'B',
    car_id              INT UNSIGNED NULL COMMENT 'Assigned car ID'
) ENGINE=InnoDB COMMENT='Driver management';


CREATE TABLE cars (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand               VARCHAR(50)  NOT NULL,
    model               VARCHAR(80)  NOT NULL,
    year                YEAR         NOT NULL,
    license_plate       VARCHAR(15)  NOT NULL UNIQUE,
    fuel_type           ENUM('gasoline','diesel','hybrid','electrical','lpg','other') NOT NULL DEFAULT 'gasoline',
    mileage             INT UNSIGNED NOT NULL DEFAULT 0,
    status              ENUM('active','in_service','reserve','decommissioned') NOT NULL DEFAULT 'active',
    last_service_date   DATE,
    next_service_date   DATE,
    driver_id           INT UNSIGNED NULL COMMENT 'Current assigned driver ID',
    notes               TEXT,
    capacity            DECIMAL(3,1) NOT NULL,
    power               INT UNSIGNED NOT NULL,
    consumption         DECIMAL(4,1) NOT NULL COMMENT 'L/100km',
    
    CONSTRAINT fk_car_driver
        FOREIGN KEY (driver_id) REFERENCES drivers(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    INDEX idx_status (status),
    INDEX idx_brand_model (brand, model),
    INDEX idx_license_plate (license_plate)
) ENGINE=InnoDB COMMENT='Fleet vehicle management';

CREATE TABLE documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    car_id INT UNSIGNED NOT NULL,
    doc_type VARCHAR(50) NOT NULL,
    provider VARCHAR(100),
    issue_date DATE,
    expiry_date DATE,
    file_path VARCHAR(255),
    FOREIGN KEY (car_id)
        REFERENCES cars(id)
        ON DELETE CASCADE
);

CREATE TABLE service_orders (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    car_id              INT UNSIGNED NULL,
    appointment_date    DATE,
    intervention_type   ENUM('revision','repair','inspection','other'),
    description         TEXT,
    status              ENUM('scheduled','in_progress','completed','canceled'),
    service_center      VARCHAR(100),
    estimated_cost      DECIMAL(10,2),

    CONSTRAINT fk_order_car
        FOREIGN KEY(car_id) REFERENCES cars(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Maintenance and repair orders';



CREATE TABLE tires (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    car_id              INT UNSIGNED NOT NULL,
    brand               VARCHAR(50),
    size                VARCHAR(20),
    tire_type           ENUM('summer','winter','all-season'),
    condition_status    VARCHAR(50),
    wear_level       INT UNSIGNED

    CONSTRAINT fk_tire_car
        FOREIGN KEY(car_id) REFERENCES cars(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Tire inventory';


CREATE TABLE users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    company_name VARCHAR(150),
    password VARCHAR(255) NOT NULL,
    role ENUM('manager','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@autodock.com', MD5('admin123'), 'manager');

SET FOREIGN_KEY_CHECKS = 1;