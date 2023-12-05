<div class="wrap">
    <h2><?php

		esc_html_e( 'DT App Launcher', 'dt_launcher' ) ?></h2>

	<?php include DT\Launcher\plugin()->templates_path . '/includes/admin-tabs.php' ?>

    <div class="wrap">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">

					<?php if ( $error ?? '' ): ?>
                        <div class="notice notice-error is-dismissible">
                            <p>
								<?php echo esc_attr( $error ) ?>
                            </p>
                        </div>
					<?php endif; ?>

                    <!-- Main Column -->

                    <form method="post">
						<?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>

                    </form>
                    <br>

                    <!-- End Main Column -->
                </div><!-- end post-body-content -->
                <div id="postbox-container-1" class="postbox-container">
                    <!-- Right Column -->


                    <!-- End Right Column -->
                </div><!-- postbox-container 1 -->
                <div id="postbox-container-2" class="postbox-container">
                </div><!-- postbox-container 2 -->
            </div><!-- post-body meta box container -->
        </div><!--poststuff end -->
    </div><!-- wrap end -->
</div>