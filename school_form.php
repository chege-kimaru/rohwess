<?php
require_once __DIR__ . '/bootstrap.php';

requireAdmin();

$campus_id = $_GET['campus_id'];
if (!$campus_id) {
    header("location: campus_form.php");
}
try {
    $stmt = $db->prepare("SELECT * FROM campuses WHERE id = ?");
    $stmt->execute([$campus_id]);
    $campus = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campus) {
        header("location: campus_form.php");
    }

    $stmt = $db->prepare("SELECT * FROM schools WHERE campus_id = ?");
    $stmt->execute([$campus_id]);
    $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a class="active item" href="school_form.php?campus_id=<?php echo $campus['id']; ?>">
            <?php echo $campus['name'] ?>
        </a>
        <?php include 'admin_nav.php' ?>
    </div>
    <h1><?php echo $campus['name'] ?></h1>
    <hr>
    <div class="ui stackable grid">
        <div class="ten wide column">
            <h2>Schools</h2>
            <table class="ui selectable inverted table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date Added</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($schools as $school) : ?>
                    <tr>
                        <td><?php echo $school['name']; ?></td>
                        <td><?php echo $school['created_at']; ?></td>
                        <td>
                            <a href="dept_form.php?campus_id=<?php echo $campus['id']; ?>&school_id=<?php echo $school['id']; ?>">Go</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="six wide column">
            <h2>Add School</h2>
            <form class="ui form" method="POST" action="<?php echo htmlspecialchars('add_school.php'); ?>">
                <div class="field">
                    <input type="hidden" name="campus_id" value="<?php echo $campus_id; ?>">
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