<?php

use function DT\Home\route_url;

/**
 * @var array $app
 * @var string $url
 */
$this->layout( 'layouts/web-view' );
?>


<iframe src="<?php echo htmlspecialchars( $url ); ?>" width="100%" height="650" frameborder="0"></iframe>

<?php
$this->insert( 'partials/return-to-launcher-button' );
?>
