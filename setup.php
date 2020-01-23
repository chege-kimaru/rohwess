<?php
require_once __DIR__ . "/bootstrap.php";

$db->beginTransaction();

try {
    $createCampus = $db->exec("CREATE TABLE campuses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP
    )");

    $createSchool = $db->exec("CREATE TABLE schools (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    campus_id INT(11) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campus_id) REFERENCES campuses(id)
    )");

    $createDepartment = $db->exec("CREATE TABLE departments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    school_id INT(11) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id)
    )");

    $createUnit = $db->exec("CREATE TABLE units (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    dept_id INT(11) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dept_id) REFERENCES departments(id)
    )");

    $createUsers = $db->exec("CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id int(10) UNSIGNED,
   `hash` VARCHAR( 32 ) NOT NULL DEFAULT '0',
   `active` INT( 1 ) NOT NULL DEFAULT '0',
    created_at datetime DEFAULT CURRENT_TIMESTAMP
    )");

    $createCampusUploads = $db->exec("CREATE TABLE campus_uploads (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL,
    campus_id INT(11) NOT NULL,
    school_id INT(11) NOT NULL,
    dept_id INT(11) NOT NULL,
    unit_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campus_id) REFERENCES campuses(id),
    FOREIGN KEY (school_id) REFERENCES schools(id),
    FOREIGN KEY (dept_id) REFERENCES departments(id),
    FOREIGN KEY (unit_id) REFERENCES units(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    $createCampusUploads2 = $db->exec("CREATE TABLE campus_uploads_2 (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL,
    campus_id INT(11) NOT NULL,
    school_id INT(11) NOT NULL,
    dept_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    unit_name VARCHAR (255) NOT NULL,
    unit_code VARCHAR (255) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campus_id) REFERENCES campuses(id),
    FOREIGN KEY (school_id) REFERENCES schools(id),
    FOREIGN KEY (dept_id) REFERENCES departments(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    $db->commit();
    header('location: index.php');

} catch (\Exception $e) {

    $db->rollBack();

    if ($e->getCode() == '42S01') {
        header('location: index.php');
    }

    echo $e->getMessage();
}