<?php

$this->layout( 'layouts/plugin' );
?>

<div>
    <h1 class="training"> <?php $this->esc_html_e( 'Training', 'dt_home' ); ?></h1>

    <video-list training-data='<?php echo htmlspecialchars( $data ); ?>'></video-list>

</div>
