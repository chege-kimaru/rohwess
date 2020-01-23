<?php
require_once __DIR__. "/bootstrap.php";

$campus_id = $_GET['campus_id'];
$school_id = $_GET['school_id'];
$dept_id = $_GET['dept_id'];

if(!$campus_id && !$school_id && !$dept_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM campuses");
        $stmt->execute();
        $campuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($campuses);
    } catch (\Exception $e) {
//    throw $e;
    }
}

if ($campus_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM schools WHERE campus_id = ?");
        $stmt->execute([$campus_id]);
        $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($schools);
    } catch (\Exception $e) {
//    throw $e;
    }
}

if ($school_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM departments WHERE school_id = ?");
        $stmt->execute([$school_id]);
        $depts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($depts);
    } catch (\Exception $e) {
//    throw $e;
    }
}

if ($dept_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM units WHERE dept_id = ?");
        $stmt->execute([$dept_id]);
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($units);
    } catch (\Exception $e) {
//    throw $e;
    }
}

