<?php
/**
 * @var string $html
 */
?>
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

</style>

<div class="container">
	<?php echo esc_html( $html ); ?>
</div>

<?php
$this->insert( 'partials/return-to-launcher-button' );
?>
