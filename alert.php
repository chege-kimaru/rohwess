<?php if ($session->getFlashBag()->has('error')) { ?>
    <div class="ui negative message">
        <i class="close icon"></i>
        <div class="header">
            Error
        </div>
        <?php print display_errors(); ?>
    </div>
<?php } ?>
<?php if ($session->getFlashBag()->has('success')) { ?>
    <div class="ui success message">
        <i class="close icon"></i>
        <div class="header">
            Success
        </div>
        <?php print display_success(); ?>
    </div>
<?php } ?>