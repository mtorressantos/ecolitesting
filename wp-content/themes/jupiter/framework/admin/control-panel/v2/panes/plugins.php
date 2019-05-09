<?php
$api_key = get_option('artbees_api_key');
$is_registered = !empty($api_key) ? '' : 'mka-call-to-register-product';
?>
<div class="mka-cp-pane-box <?php echo $is_registered; ?>" id="mk-cp-plugins">
    <div class="mka-cp-plugins-list mka-cp-installed-plugins">
        <div class="mka-cp-pane-title">
            <?php esc_html_e( 'Active Plugins', 'mk_framework' ); ?>
            <!-- <div class="mka-wrap mka-tip-wrap">
                <a class="mka-tip" href="#">
                    <span class="mka-tip-icon">
                        <span class="mka-tip-arrow">
                        </span>
                    </span>
                    <span class="mka-tip-ripple">
                    </span>
                </a>
                <div class="mka-tip-content">
                </div>
            </div> -->
        </div>
        <div id="js__mka-installed-plugins">
            <svg class="mka-spinner" width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                <circle class="mka-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
            </svg>
        </div>
    </div>
    <div class="mka-cp-plugins-list">
        <div class="mka-cp-pane-title">
            <?php esc_html_e( 'New Plugins', 'mk_framework' ); ?>
            <!-- <div class="mka-wrap mka-tip-wrap">
                <a class="mka-tip" href="#">
                    <span class="mka-tip-icon">
                        <span class="mka-tip-arrow">
                        </span>
                    </span>
                    <span class="mka-tip-ripple">
                    </span>
                </a>
                <div class="mka-tip-content">
                </div>
            </div> -->
        </div>
        <div id="js__mka-new-plugins">
            <svg class="mka-spinner" width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                <circle class="mka-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
            </svg>
        </div>
    </div>
</div>
