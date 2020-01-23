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

$code = $_POST['code'];
$name = $_POST['name'];
$campus_id = $_POST['campus_id'];
$school_id = $_POST['school_id'];
$dept_id = $_POST['dept_id'];
if(!$name || !$dept_id || !$code || !$campus_id || !$school_id) {
    $session->getFlashBag()->add('error', 'Please fill all the required fields');
    header("location: unit_form.php?campus_id=$campus_id&school_id=$school_id&dept_id=$dept_id");
}

try {
    $stmt = $db->prepare("INSERT INTO units (name, code, dept_id) VALUES (:name, :code, :dept_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':dept_id', $dept_id);
    $stmt->execute();
    $db->lastInsertId();
    $session->getFlashBag()->add('success', 'Successfully added new Unit.');
    header("location: unit_form.php?campus_id=$campus_id&school_id=$school_id&dept_id=$dept_id");
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', "OOps, an error occurred while adding unit. {$e->getMessage()} Please try again or consult admin.");
    header("location: unit_form.php?campus_id=$campus_id&school_id=$school_id&dept_id=$dept_id");
}