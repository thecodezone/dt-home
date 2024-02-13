<header>
    <h1><?php $this->esc_html_e( 'App', 'dt_home' ); ?></h1>
</header>

<div>
	<?php echo $this->section( 'content' ) ?>
</div>

<footer>
    <p>
		<?php $this->esc_html_e( 'Copyright ', 'dt_home' ); ?>

		<?php echo $this->e( gmdate( 'Y' ) ); ?>
    </p>
</footer>