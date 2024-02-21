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
    <script>
        document.addEventListener('app-selected', (event) => {
            const appUrl = event.detail.url;
            // Logic to extract a slug or identifier from the URL
            const appSlug = extractSlugFromUrl(appUrl); // Implement this function based on your URL structure

            // Navigate to the new route
            // This will depend on your routing setup, for example:
            // window.location.href = `/home/app/${encodeURIComponent(appSlug)}`;
        });
    </script>
</div>
