<?php
use function DT\Home\route_url;
?>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

</style>

<div class="container">
	<?php echo $html; ?>
</div>

<a href="<?php echo esc_url( route_url() ); ?>" class="icon-link">
    <sp-icon-view-grid class="sp-icon-view-grid"></sp-icon-view-grid>
</a>
