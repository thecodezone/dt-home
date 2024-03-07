<sp-theme
        theme="spectrum"
        color="light"
        scale="medium"
>
    <div class="plugin cloak">
        <div class="plugin__main">
            <div class="container">
				<?php $user = wp_get_current_user();
				?>

				<?php if ( $user->has_cap( 'access_disciple_tools' ) ): ?>
					<?php
					$magic_url  = DT\Home\magic_url();
					$training   = $magic_url . '/training';
					$logout     = $magic_url . '/logout';
					$home       = '/home';
					$menu_items = json_encode( [
						[ 'label' => __( 'Apps', 'dt_home' ), 'href' => $home ],
						[ 'label' => __( 'Training', 'dt_home' ), 'href' => $training ],
						[ 'label' => __( 'Log Out', 'dt_home' ), 'href' => $logout ],
					] );
					?>
                    <menu-component menuItems='<?php echo $menu_items; ?>'></menu-component>

				<?php endif; ?>
				<?php echo $this->section( 'content' ) ?>
            </div>
        </div>

        <div class="plugin__footer">
            <div class="container">
				<?php echo $this->section( 'footer' ) ?>
            </div>
        </div>
    </div>
</sp-theme>