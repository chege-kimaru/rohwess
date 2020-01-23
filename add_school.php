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
if(!$name || !$campus_id) {
    $session->getFlashBag()->add('error', 'Please fill all the required fields');
    header("location: school_form.php?campus_id=$campus_id");
}

try {
    $stmt = $db->prepare("INSERT INTO schools (name, campus_id) VALUES (:name, :campus_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':campus_id', $campus_id);
    $stmt->execute();
    $db->lastInsertId();
    $session->getFlashBag()->add('success', 'Successfully added new school.');
    header("location: school_form.php?campus_id=$campus_id");
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', "OOps, an error occurred while adding school. {$e->getMessage()} Please try again or consult admin.");
    header("location: school_form.php?campus_id=$campus_id");
}