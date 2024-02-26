<?php
/**
 * @var string $magic_link
 * @var string $data
 * @var string $app_url
 * @var string $subpage_url
 * @var WP_User $user
 */
$this->layout('layouts/web-view');
?>

<div>

    <?php
    if (isset($desired_app) && isset($desired_app['url'])) {
        $url = $desired_app['url'];
        ?>

        <iframe src="<?php echo htmlspecialchars($url); ?>" width="400" height="600" frameborder="0"></iframe>
        <?php
    } else {
        echo "URL not found or the desired app is not set.";
    }
    ?>


    <div>
        <?php echo $this->section('content') ?>
    </div>
</div>
