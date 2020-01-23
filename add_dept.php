<?php
/**
 * Created by PhpStorm.
 * User: Kevin Kimaru Chege
 * Date: 5/28/2018
 * Time: 9:29 PM
 */
require_once __DIR__. '/bootstrap.php';

requireAdmin();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
    header('location: index.php');
}

$name = $_POST['name'];
$campus_id = $_POST['campus_id'];
$school_id = $_POST['school_id'];
if(!$name || !$school_id || !$campus_id) {
    $session->getFlashBag()->add('error', 'Please fill all the required fields');
    header("location: dept_form.php?campus_id=$campus_id&school_id=$school_id");
}

try {
    $stmt = $db->prepare("INSERT INTO departments (name, school_id) VALUES (:name, :school_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':school_id', $school_id);
    $stmt->execute();
    $db->lastInsertId();
    $session->getFlashBag()->add('success', 'Successfully added new Department.');
    header("location: dept_form.php?campus_id=$campus_id&school_id=$school_id");
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', "OOps, an error occurred while adding department. {$e->getMessage()} Please try again or consult admin.");
    header("location: dept_form.php?campus_id=$campus_id&school_id=$school_id");
}