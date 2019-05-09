<?php
$compatibility = new Compatibility();
$compatibility->setSchedule('off');
wp_print_request_filesystem_credentials_modal();
?>

<div class="mka-cp-header">
    <div class="mka-cp-branding">
        <div class="mka-cp-jupiter-logo">
            <span>
            </span>
        </div>
        <strong>
            <span><?php echo THEME_NAME; ?> <?php _e('Control Panel', 'mk_framework');?></span>
        </strong>
    </div>
    <div class="mka-cp-theme-version"><?php _e('Version', 'mk_framework');?> <?php echo get_option('mk_jupiter_theme_current_version'); ?></div>
</div>

<?php echo $compatibility->compatibilityCheck(); ?>