<?php
require_once __DIR__. '/bootstrap.php';

requireAuth();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
    header("location: index.php");
}

$my_doc = $_FILES['upload']['name'];
$tmp = $_FILES['upload']['tmp_name'];
$type = $_FILES['upload']['type'];

$campus_id = $_POST['campus_id'];
$school_id = $_POST['school_id'];
$dept_id = $_POST['dept_id'];
$unit_code = $_POST['unit_code'];
$unit_name = $_POST['unit_name'];

$valid = true;
$errors;

if (!$campus_id) {
    $valid = false;
    $errors .= 'University is required <br/>';
}
if (!$school_id) {
    $valid = false;
    $errors .= 'School is required <br/>';
}
if (!$dept_id) {
    $valid = false;
    $errors .= 'Department is required <br/>';
}
if (!$unit_code) {
    $valid = false;
    $errors .= 'Unit Code is required <br/>';
}
if (!$unit_name) {
    $valid = false;
    $errors .= 'University Name is required <br/>';
}

// if(($type == "image/jpeg") || ($type == "image/jpg") || ($type == "image/png")) {

// } else {
//     $valid = false;
//     echo "This file has to be a jpeg or jpg or png.";
// }

if (!$valid) {
    $session->getFlashBag()->add('error', $errors);
    header("location: upload_form.php");
}

try {
    $stmt = $db->prepare("SELECT * FROM campuses WHERE id = ?");
    $stmt->execute([$campus_id]);
    $campus = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', 'The selected campus does not exist.');
    header("location: upload_form.php");
}
try {
    $stmt = $db->prepare("SELECT * FROM schools WHERE id = ?");
    $stmt->execute([$school_id]);
    $school = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', 'The selected school does not exist.');
    header("location: upload_form.php");
}
try {
    $stmt = $db->prepare("SELECT * FROM depts WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', 'The selected department does not exist.');
    header("location: upload_form.php");
}


if (!$campus || !$school || !$dept) {
    $session->getFlashBag()->add('error', 'Invalid data has been submitted. Please try again.');
    header("location: upload_form.php");
}

$file_campus = $campus['id'];
$file_unit = $unit_code;
$dir = strtolower(str_replace(" ", "_", "uploads/$file_campus/$file_unit"));
$file_name = strtolower(str_replace(" ", "_", $my_doc));
$file_path = $dir . "/" . $file_name;

try {
    mkdir($dir, 0777, true);
    move_uploaded_file($tmp, $file_path);

    $stmt = $db->prepare("INSERT INTO campus_uploads_2 (file_path, campus_id, school_id, dept_id, user_id, unit_name, unit_code) 
      VALUES (:file_path, :campus_id, :school_id, :dept_id, :user_id, :unit_name, :unit_code)");
    $stmt->bindParam(':file_path', $file_path);
    $stmt->bindParam(':campus_id', $campus_id);
    $stmt->bindParam(':school_id', $school_id);
    $stmt->bindParam(':dept_id', $dept_id);
    $stmt->bindParam(':user_id', user('id'));
    $stmt->bindParam(':unit_name', $unit_name);
    $stmt->bindParam(':unit_code', $unit_code);
    $stmt->execute();
    $session->getFlashBag()->add('Success', 'Successfully uploaded resource');
    header("location: index.php");
} catch (\Exception $e) {
    $session->getFlashBag()->add('error', "OOps, an error occurred while uploading resource. {$e->getMessage()} Please try again or consult admin.");
    header("location: upload_form.php");
}
