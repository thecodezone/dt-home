<div class="cloak">
    <header>
        <h3><?php $this->esc_html_e( '', 'dt_home' ); ?></h3>
    </header>

    <div>
		<?php echo $this->section( 'content' ) ?>
    </div>

    <footer class="footer">
        <p>
			<?php $this->esc_html_e( 'Copyright ', 'dt_home' ); ?>

			<?php echo $this->e( gmdate( 'Y' ) ); ?>
        </p>
    </footer>

</div>
