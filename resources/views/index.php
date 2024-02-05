<?php
$this->layout('layouts/plugin');
?>

<div class="container">

    <div>
        <?php echo $this->section('content') ?>
    </div>

    <b>
        Name: <?php echo $this->e($user->user_nicename); ?>
    </b>

    <a href="<?php echo esc_url($subpage_url); ?>">
        <?php $this->esc_html_e('Visit subpage', 'dt-launcher'); ?>
    </a>


</div>

