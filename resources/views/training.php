<?php
$this->layout( 'layouts/plugin' );
?>

<div>
    <?php foreach ($data as $training) : ?>
        <?php echo esc_html($training['name']); ?><br>
        <?php echo $training['embed_video']; ?><br>
    <?php endforeach; ?>
</div>

