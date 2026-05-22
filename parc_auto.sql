SET FOREIGN_KEY_CHECKS = 0;


CREATE DATABASE IF NOT EXISTS parc_auto
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_romanian_ci;

USE parc_auto;
CREATE TABLE drivers (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name          VARCHAR(60)  NOT NULL,
    last_name           VARCHAR(60)  NOT NULL,
    cnp                 CHAR(13)     UNIQUE,
    birth_date          DATE,
    phone               VARCHAR(20)  NOT NULL,
    email               VARCHAR(100),
    license_category    VARCHAR(20)  NOT NULL DEFAULT 'B',
    license_expiry_date DATE         NOT NULL,
    status              ENUM('active','suspended','inactive') NOT NULL DEFAULT 'active',
    car_id              INT UNSIGNED NULL COMMENT 'Assigned car ID',
    notes               TEXT

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
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doc_type            ENUM('identity','license','other'),
    issue_date          DATE,
    expiry_date         DATE,
    status              ENUM('valid','expired','pending'),
    driver_id           INT UNSIGNED NULL,

    CONSTRAINT fk_document_driver
        FOREIGN KEY (driver_id) REFERENCES drivers(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Driver personal documents';

CREATE TABLE service_orders (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    car_id              INT UNSIGNED NULL,
    appointment_date    DATE,
    intervention_type   ENUM('revision','repair','inspection','other'),
    description         TEXT,
    status              ENUM('scheduled','in_progress','completed','canceled'),
    service_center      VARCHAR(100),
    estimated_cost      DECIMAL(10,2),
    final_cost          DECIMAL(10,2),

    CONSTRAINT fk_order_car
        FOREIGN KEY(car_id) REFERENCES cars(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Maintenance and repair orders';

CREATE TABLE service_history (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_date        DATE,
    description         TEXT,
    cost                DECIMAL(10,2),
    mileage             INT UNSIGNED,
    order_id            INT UNSIGNED NULL,

    CONSTRAINT fk_history_order
        FOREIGN KEY(order_id) REFERENCES service_orders(id)
        ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Service intervention logs';

CREATE TABLE insurances (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    insurance_type      ENUM('liability','comprehensive','both'),
    company             VARCHAR(100),
    issue_date          DATE,
    expiry_date         DATE,
    policy_number       VARCHAR(50),
    car_id              INT UNSIGNED NULL,
    cost                DECIMAL(10,2),

    CONSTRAINT fk_insurances_car
        FOREIGN KEY(car_id) REFERENCES cars(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Insurance policies';

CREATE TABLE vignettes (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vignette_type       ENUM('7_days','30_days','90_days','annual'),
    vignette_days       INT,
    expiry_date         DATE,
    country             VARCHAR(50),
    vignette_number     VARCHAR(50),
    car_id              INT UNSIGNED NULL,

    CONSTRAINT fk_vignette_car
        FOREIGN KEY(car_id) REFERENCES cars(id)
        ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Road tax management';

CREATE TABLE tires (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand               VARCHAR(50),
    size                VARCHAR(20),
    tire_type           ENUM('summer','winter','all-season'),
    tire_condition      ENUM('new','used','worn') NOT NULL DEFAULT 'new',
    purchase_date       DATE,
    usage_mileage       INT UNSIGNED
) ENGINE=InnoDB COMMENT='Tire inventory';

CREATE TABLE tire_installations (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    car_id              INT UNSIGNED,
    tire_id             INT UNSIGNED,
    install_date        DATE,
    wheel_position      ENUM('front-left','front-right','rear-right','rear-left','spare'),
    mileage_at_install  INT UNSIGNED,

    CONSTRAINT fk_install_car
        FOREIGN KEY(car_id) REFERENCES cars(id) ON DELETE CASCADE,
    CONSTRAINT fk_install_tire
        FOREIGN KEY(tire_id) REFERENCES tires(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Vehicle tire installation logs';
CREATE TABLE users(
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username            VARCHAR(100)    NOT NULL UNIQUE,
    email               VARCHAR(100)    NOT NULL UNIQUE,
    password            VARCHAR(255)    NOT NULL,
    role                ENUM('manager','user') NOT NULL DEFAULT 'user',
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB COMMENT='System users';


INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@autodock.com', MD5('admin123'), 'manager');

SET FOREIGN_KEY_CHECKS = 1;