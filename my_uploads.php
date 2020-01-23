<?php
require_once __DIR__ . "/bootstrap.php";
requireAuth();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rohwess Upload</title>
    <link rel="stylesheet" href="public/css/semantic.min.css">
</head>
<body>
<div class="ui container">
    <div class="ui menu">
        <a class="active item" href="index.php">
            Home
        </a>
        <?php include 'admin_nav.php' ?>
    </div>
    <?php include 'alert.php'; ?>
    <h2>Uploads</h2>
    <?php
    $conn = new mysqli("localhost", "root", "kevinkimaru", "rowhessupload2");
    if ($conn->connect_error) {
        die ("Oops connection Error: " . $conn->connect_error);
    }
    $query = "SELECT * FROM campuses";
    $campuses = $conn->query($query);
    $query = "SELECT c.name AS campus, s.name AS school, d.name AS dept, cu.unit_name AS unit_name, cu.unit_code as unit_code, cu.file_path 
FROM campus_uploads_2 AS cu
LEFT JOIN campuses AS c ON cu.campus_id = c.id 
LEFT JOIN schools AS s ON cu.school_id = s.id 
LEFT JOIN departments AS d ON cu.dept_id = d.id 
WHERE cu.user_id = ".user('id');
    $uploads = $conn->query($query);
    $num = $uploads->num_rows;
    ?>
    <table class="ui selectable inverted table">
        <thead>
        <tr>
            <th>Campus</th>
            <th>School</th>
            <th>Department</th>
            <th>Unit Name</th>
            <th>Unit Code</th>
            <th>Download</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($num > 0) {
            while ($upload = $uploads->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $upload['campus'] . "</td>";
                echo "<td>" . $upload['school'] . "</td>";
                echo "<td>" . $upload['dept'] . "</td>";
                echo "<td>" . $upload['unit_name'] . "</td>";
                echo "<td>" . $upload['unit_code'] . "</td>";
                echo "<td><a href='" . $upload['file_path'] . "' target='_blank'>Download</a></td>";
                echo "</tr>";
            }
        } else {
            echo "You have no uploads.";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>