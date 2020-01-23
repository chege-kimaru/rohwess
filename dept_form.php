<?php

require_once __DIR__ . '/bootstrap.php';

requireAdmin();

$campus_id = $_GET['campus_id'];
$school_id = $_GET['school_id'];
if (!$campus_id) {
    header("location: campus_form.php");
}
if (!$school_id) {
    header("location: school_form.php?campus_id=$campus_id");
}
try {
    $stmt = $db->prepare("SELECT * FROM campuses WHERE id = ?");
    $stmt->execute([$campus_id]);
    $campus = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campus) {
        header("location: campus_form.php");
    }

    $stmt = $db->prepare("SELECT * FROM schools WHERE id = ?");
    $stmt->execute([$school_id]);
    $school = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$school) {
        header("location: school_form.php?campus_id=$campus_id");
    }

    $stmt = $db->prepare("SELECT * FROM departments WHERE school_id = ?");
    $stmt->execute([$school_id]);
    $depts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
//    throw $e;
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Rowhess Upload</title>
        <link rel="stylesheet" href="public/css/semantic.min.css">
    </head>
<body>
<div class="ui container">
    <div class="ui menu">
        <a class="item" href="index.php">
            Home
        </a>
        <a class="item" href="campus_form.php">
            Campuses
        </a>
        <a class="item" href="school_form.php?campus_id=<?php echo $campus['id']; ?>">
            <?php echo $campus['name'] ?>
        </a>
        <a class="active item"
           href="dept_form.php?campus_id=<?php echo $campus['id']; ?>&school_id=<?php echo $school['id']; ?>">
            <?php echo $school['name'] ?>
        </a>
        <?php include 'admin_nav.php' ?>
    </div>
    <?php include 'alert.php'; ?>
    <h1><?php echo $campus['name'] ?></h1>
    <h2><?php echo $school['name'] ?></h2>
    <hr>
    <div class="ui stackable grid">
        <div class="ten wide column">
            <h3>Departments</h3>
            <table class="ui selectable inverted table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date Added</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($depts as $dept) : ?>
                    <tr>
                        <td><?php echo $dept['name']; ?></td>
                        <td><?php echo $dept['created_at']; ?></td>
                        <td>
                            <a href="unit_form.php?campus_id=<?php echo $campus['id']; ?>&school_id=<?php echo $school['id']; ?>
                    &dept_id=<?php echo $dept['id']; ?>">
                                Go
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="six wide column">
            <h2>Add Department</h2>
            <form class="ui form" method="POST" action="<?php echo htmlspecialchars('add_dept.php'); ?>">
                <div class="field">
                    <input type="hidden" name="campus_id" value="<?php echo $campus['id']; ?>">
                </div>
                <div class="field">
                    <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
                </div>
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name">
                </div>
                <button class="ui button" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>