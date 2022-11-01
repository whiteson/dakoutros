<?php  require dirname(__FILE__) . "/options_header.php"; ?>

    <form method="post" action="options.php">
        <?php  settings_fields('pdfgen-page4');
        do_settings_sections('pdfcachesettings');
        ?>
        <?php  submit_button(); ?>
    </form>

    <hr>

</div>