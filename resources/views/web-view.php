<?php
/**
 * @var array $app
 * @var string $url
 */
$this->layout( 'layouts/web-view' );
?>

<div>
    <iframe src="<?php echo htmlspecialchars( $url ); ?>" width="400" height="600" frameborder="0"></iframe>
</div>
