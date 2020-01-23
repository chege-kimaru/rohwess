<?php
require_once __DIR__ . '/bootstrap.php';

requireAdmin();

try {
    $stmt = $db->prepare("SELECT * FROM campuses");
    $stmt->execute();
    $campuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a class="active item" href="campus_form.php">
           Campuses
        </a>

        <?php include 'admin_nav.php' ?>
    </div>
    <?php include 'alert.php'; ?>
    <div class="ui stackable grid">
        <div class="ten wide column">
            <h2>Campuses</h2>
            <table class="ui selectable inverted table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date Added</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($campuses as $campus) : ?>
                    <tr>
                        <td><?php echo $campus['name']; ?></td>
                        <td><?php echo $campus['created_at']; ?></td>
                        <td><a href="school_form.php?campus_id=<?php echo $campus['id']; ?>">Go</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="six wide column">
            <h2>Add Campus</h2>
            <form class="ui form" method="POST" action="<?php echo htmlspecialchars('add_campus.php'); ?>">
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