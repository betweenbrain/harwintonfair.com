<div class="bar">
    <div class="wrapper">
        <?php if (is_active_sidebar('footer_menu')) : ?>
            <nav class="footer-navigation" role="navigation">
                <?php dynamic_sidebar('footer_menu'); ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
<?php
// Required for customizer admin functionality.
wp_footer();
?>
</body>

</html>