<header>
    <h1><?php $this->esc_html_e( 'Plugin', 'dt_launcher' ); ?></h1>
</header>

<div class="container">
    <div>
        <?php echo $this->section( 'content' ) ?>
    </div>
</div>
<footer class="footer">
    <p>
		<?php $this->esc_html_e( 'Copyright ', 'dt_launcher' ); ?>

		<?php echo $this->e( gmdate( 'Y' ) ); ?>
    </p>
</footer>
