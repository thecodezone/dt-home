<?php

$this->layout('layouts/plugin');
?>

<dt-tile>
    <h1 class="training"> <?php $this->esc_html_e('Training', 'dt_home'); ?></h1>

    <video-list training-data='<?php echo htmlspecialchars($data); ?>'></video-list>

</dt-tile>

