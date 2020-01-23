<?php require_once __DIR__."/bootstrap.php"; ?>

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
        <?php include 'admin_nav.php'?>
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
LEFT JOIN departments AS d ON cu.dept_id = d.id ";
    $uploads = $conn->query($query);
    $num = $uploads->num_rows;
    ?>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="ui form">
        <div class="field">
            <div class="three fields">
                <div class="field">
                    <select name="campus_id" class="ui selection dropdown">
                        <?php
                        while ($campus = $campuses->fetch_assoc()) {
                            echo "<option value='" . $campus['id'] . "'>" . $campus['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="field">
                    <input type="text" name="unit_c" placeholder="Unit Code">
                </div>
                <div class="field">
                    <button class="ui button" name="submit">search</button>
                </div>
            </div>
        </div>
    </form>
    <?php
    if (isset($_REQUEST['submit'])) {
        $campus_id = $_GET['campus_id'];
        $unit_c = $_GET['unit_c'];
        $unit_c_terms = explode(" ", $unit_c);
        $query .="WHERE ";
        $query .= "cu.campus_id = $campus_id ";
        $i = 0;
        foreach ($unit_c_terms as $uc) {
            $i++;
            if ($i == 1) {
                $query .= "AND (cu.unit_code LIKE '%$uc%' ";
            }
            $query .= "OR cu.unit_name LIKE '%$uc%' ";
            $query .= "OR cu.unit_code LIKE '%$uc%' ";
        }
        $query .= ");";

        $uploads = $conn->query($query);
        $num = $uploads->num_rows;
        echo "<h3>Results: $num results";
    }
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
            echo "No results found";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>