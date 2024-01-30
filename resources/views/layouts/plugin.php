<header>
    <h1><?php $this->esc_html_e( 'Plugin', 'dt_launcher' ); ?></h1>
</header>

<div class="container">
    <dt-tile>
        <div class="section__inner">
            <div>
                <dt-copy-text value="<?php echo DT\Launcher\magic_url(); ?>"></dt-copy-text>
            </div>
            <div>
                <?php echo $this->section( 'content' ) ?>
            </div>
        </div>

    </dt-tile>

</div>
<footer class="footer">
    <p>
		<?php $this->esc_html_e( 'Copyright ', 'dt_launcher' ); ?>

		<?php echo $this->e( gmdate( 'Y' ) ); ?>
    </p>
</footer>
