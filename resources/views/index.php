<?php
$this->layout('layouts/plugin');
?>

<dt-launcher-app-grid id="appGrid" app-data='<?php echo htmlspecialchars($data); ?>'>
    <!-- Add more app icons as needed -->
</dt-launcher-app-grid>
<div>
    <b>
        Name: <?php echo $this->e($user->user_nicename); ?>
    </b>
</div>

<a href="<?php echo esc_url($subpage_url); ?>">
    <?php $this->esc_html_e('Visit subpage', 'dt-launcher'); ?>
</a>
