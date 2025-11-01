CREATE TABLE `business_plans` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_by` INT(10) UNSIGNED NOT NULL COMMENT 'FK to app_users.id, From Session::get(app_user_id)',
    `add_user_name` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Fetched from app_users table',
    `business_category_id` INT(11) DEFAULT NULL COMMENT 'ID from business_category table',
    `business_category_name` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Name from business_category table',
    `loan_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Loan amount requested',
    `extra_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Any extra amount',
    `number_of_days` INT(11) DEFAULT 0 COMMENT 'Number of days for loan',
    `membership_charge` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Membership charge',
    `emi_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'EMI amount',
    `processing_charge` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Processing fee',
    `loan_insurance_charge` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Loan insurance charge',
    `other_charges` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Any other charges',
    `interest_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Interest amount',
    `interest_rate` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Interest rate',
    `final_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Final amount to be paid',
    `status` TINYINT(1) DEFAULT '1' COMMENT '0=Inactive,1=Active',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created at',
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated at',
    PRIMARY KEY (`id`),
    KEY `fk_user_by` (`user_by`),
    CONSTRAINT `fk_user_by` FOREIGN KEY (`user_by`) REFERENCES `app_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `monthly_update` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_by` INT(10) UNSIGNED NOT NULL COMMENT 'FK to app_users.id, From Session::get(app_user_id)',
    `add_user_name` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT 'Unknown' COMMENT 'Fetched from app_users table',
    `month_name` VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Name of the month',
    `director_loan` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Amount of director loan',
    `bank_loan` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Amount of bank loan',
    `investment_for_invertor` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Investment amount for investor',
    `director_salary` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Salary of the director',
    `staff_salary` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Salary for the staff',
    `office_rent` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Monthly office rent',
    `electricity_bill` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Electricity bill for the month',
    `recharge_bill` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Recharge bill for the month',
    `furniture_amount` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Amount spent on furniture',
    `other_expences` DECIMAL(10,2) DEFAULT '0.00' COMMENT 'Any other expenses for the month',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Record created at',
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record updated at',
    PRIMARY KEY (`id`),
    KEY `idx_monthly_user_by` (`user_by`),
    CONSTRAINT `fk_monthly_update_user_by` FOREIGN KEY (`user_by`) REFERENCES `app_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



