<?php

$this->layout( 'layouts/plugin' );
/**
 * @var string $data
 */
?>
<h1 class="training"> <?php $this->esc_html_e( 'Training', 'dt_home' ); ?></h1>
<dt-tile>
    <dt-home-video-list training-data='<?php echo htmlspecialchars( $data ); ?>'></dt-home-video-list>
</dt-tile>

