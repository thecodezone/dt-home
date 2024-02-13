<div class="cloak">
    <header>
        <h1><?php $this->esc_html_e( 'Plugin', 'dt_home' ); ?></h1>
    </header>

    <div class="container">
        <dt-tile>
            <div class="section__inner">
				<?php $user = wp_get_current_user();
				?>

				<?php if ( $user->has_cap( 'access_disciple_tools' ) ): ?>
					<?php
					$magic_url = DT\Home\magic_url();
					$training  = $magic_url . '/training';
					$logout    = $magic_url . '/logout';
					$menuItems = json_encode( [
						[ 'label' => __( 'Training', 'dt_home' ), 'href' => $training ],
						[ 'label' => __( 'Log Out', 'dt_home' ), 'href' => $logout ],
					] );
					?>
                    <menu-component menuItems='<?php echo $menuItems; ?>'></menu-component>

				<?php endif; ?>
				<?php echo $this->section( 'content' ) ?>
            </div>
        </dt-tile>
    </div>
    <footer class="footer">
        <p>
			<?php $this->esc_html_e( 'Copyright ', 'dt_home' ); ?>

			<?php echo $this->e( gmdate( 'Y' ) ); ?>
        </p>
    </footer>

</div>

