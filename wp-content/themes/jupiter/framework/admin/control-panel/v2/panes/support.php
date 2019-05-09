 <!-- Support Pane -->
 <?php set_transient('mk_jupiter_theme_version', null); ?>
<div class="mka-cp-pane-box" id="mk-cp-support">
    <h3>
        <?php esc_html_e( 'Learn &amp; Support', 'mk_framework'); ?>
    </h3>
    <ul class="mka-cp-support-ul">
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="https://themes.artbees.net/docs/installing-fresh-copy-wordpress/" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-docs">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'Documentation', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'Read helpful resources regarding how to use Jupiter more efficiently.', 'mk_framework'); ?>
                </span>
            </a>
        </li>
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="https://themes.artbees.net/support/jupiter/videos" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-video">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'Video Tutorials', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'Watch tons of narrated video tutorials.', 'mk_framework'); ?>
                </span>
            </a>
        </li>
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="https://themes.artbees.net/support/jupiter/faq" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-faq">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'FAQ', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'The most frequent asked questions are answered here.', 'mk_framework'); ?>
                </span>
            </a>
        </li>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="https://themes.artbees.net/dashboard/tickets" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-helpdesk">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'Ask our experts', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'Any question that is not addressed on documentations? Ask it from Artbees experts. Note that you need to register to be able to submit a new ticket.', 'mk_framework'); ?>
                    <div style="color:#15b2fa">
                        <?php esc_html_e( 'Submit a new Ticket', 'mk_framework'); ?>
                    </div>
                </span>
            </a>
        </li>
		<?php } ?>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="http://forums.artbees.net/c/jupiter" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-community">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'Join Community', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'Join the Artbees themes community. It is a cool and cozy place! Help people and get help.', 'mk_framework'); ?>
                </span>
            </a>
        </li>
		<?php } ?>
		<?php if ( ! defined( 'ENVATO_HOSTED_SITE' ) ) { ?>
        <li class="mka-cp-support-items">
            <a class="mka-cp-support-link" href="https://themes.artbees.net/artbees-care" target="_blank">
                <span class="mka-cp-support-item-icon mka-cp-support-icon-customise">
                </span>
                <span class="mka-cp-support-item-title">
                    <?php esc_html_e( 'Customise Jupiter', 'mk_framework'); ?>
                </span>
                <span class="mka-cp-support-item-description">
                    <?php esc_html_e( 'Have any more customization beyond what Jupiter offers? Artbees experts are here to help.', 'mk_framework'); ?>
                    <div style="color:#15b2fa">
                        <?php esc_html_e( 'Hire an Experts', 'mk_framework'); ?>
                    </div>
                </span>
            </a>
        </li>
		<?php } ?>
    </ul>
</div>
<!-- End of Pane -->
