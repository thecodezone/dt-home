<?php
$this->layout( 'layouts/plugin' );
?>

<div>
    <?php $this->esc_html_e('Training', 'dt-launcher'); ?>
    <br><br><br>
    <video-list training-data='<?php echo htmlspecialchars($data); ?>'></video-list>

</div>
