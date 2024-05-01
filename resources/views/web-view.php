<?php
use function DT\Home\route_url;

/**
 * @var array $app
 * @var string $url
 */
$this->layout( 'layouts/web-view' );
?>

<iframe src="<?php echo htmlspecialchars( $url ); ?>" width="100%" height="650" frameborder="0"></iframe>
<a href="<?php echo esc_url( route_url() ); ?>" class="icon-link">
    <sp-icon-view-grid class="sp-icon-view-grid"></sp-icon-view-grid>
</a>
