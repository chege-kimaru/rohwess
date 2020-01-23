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
if(!$name) {
    $session->getFlashBag()->add('error', 'Please fill all the required fields');
    header('location: campus_form.php');
}

try {
    $stmt = $db->prepare("INSERT INTO campuses (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $db->lastInsertId();
    $session->getFlashBag()->add('success', 'Successfully added new campus.');
    header('location: campus_form.php');
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', "OOps, an error occurred while adding campus. {$e->getMessage()} Please try again or consult admin.");
    header('location: campus_form.php');
}