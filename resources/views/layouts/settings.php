<div class="wrap">
    <h2><?php $this->esc_html_e( 'DT App Launcher', 'dt_launcher' ) ?></h2>

    <h2 class="nav-tab-wrapper">
        <a href="admin.php?page=dt_launcher&tab=general"
           class="nav-tab <?php echo $this->esc_html( ( $tab == 'general' || ! isset( $tab ) ) ? 'nav-tab-active' : '' ); ?>">
			<?php $this->esc_html_e( 'General', 'dt_launcher' ) ?>
        </a>

        <a href="admin.php?page=dt_launcher&tab=app"
           class="nav-tab <?php echo $this->esc_html( ( $tab == 'app' || ! isset( $tab ) ) ? 'nav-tab-active' : '' ); ?>">
			<?php $this->esc_html_e( 'Apps', 'dt_launcher' ) ?>
        </a>

        <a href="admin.php?page=dt_launcher&tab=training"
           class="nav-tab <?php echo $this->esc_html(($tab == 'training' || !isset($tab)) ? 'nav-tab-active' : ''); ?>">
            <?php $this->esc_html_e('Training Videos', 'dt_launcher') ?>
        </a>
    </h2>

    <div class="wrap">
        <div id="poststuff">


            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">

					<?php if ( $error ?? '' ): ?>
                        <div class="notice notice-error is-dismissible">
                            <p>
								<?php $this->e( $error ) ?>
                            </p>
                        </div>
					<?php endif; ?>


					<?php echo $this->section( 'content' ) ?>

                    <!-- End Main Column -->
                </div><!-- end post-body-content -->
                <div id="postbox-container-1" class="postbox-container">
                    <!-- Right Column -->

					<?php echo $this->section( 'right' ) ?>
                    <!-- End Right Column -->
                </div><!-- postbox-container 1 -->
                <div id="postbox-container-2" class="postbox-container">
                </div><!-- postbox-container 2 -->
            </div><!-- post-body meta box container -->
        </div><!--poststuff end -->
    </div><!-- wrap end -->
</div>
