<?php if (isAuthenticated()): ?>
    <div class="right menu">
        <a class="item" href="upload_form.php">
            Upload
        </a>
        <?php if (isAdmin()): ?>
            <a class="item" href="campus_form.php">
                Campuses
            </a>
            <div class="item">
                <?php echo user('first_name') . " *Admin* "; ?>
                | <a href="my_uploads.php">My Uploads</a> | <a href="logout.php">Logout</a>
            </div>
        <?php else:; ?>
            <div class="right menu">
                <div class="item">
                    <?php echo user('first_name') . " " . user('last_name') ?> |
                    <a href="my_uploads.php">My Uploads</a> | <a href="logout.php">logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>