<?php
/**
 * @var string $magic_link
 * @var string $data
 * @var string $app_url
 * @var string $subpage_url
 * @var WP_User $user
 */
$this->layout('layouts/plugin');
?>

<div>
    <div>
        <dt-copy-text value="<?php echo esc_url($magic_link); ?>"></dt-copy-text>
    </div>

    <dt-home-app-grid id="appGrid" app-data='<?php echo esc_attr(htmlspecialchars($data)); ?>'
                      app-url='<?php echo esc_url($app_url); ?>'>
        <!-- Add more app icons as needed -->
    </dt-home-app-grid>
    <div>
        <?php echo $this->section('content') ?>
    </div>

    <dt-home-footer id="hiddenApps"
                    hidden-data='<?php echo esc_attr(htmlspecialchars($hidden_data)); ?>'
                    app-url-unhide='<?php echo esc_url($app_url); ?>'></dt-home-footer>
</div>
