<?php
/**
 * @var string $magic_link
 * @var string $data
 * @var string $app_url
 * @var string $subpage_url
 * @var WP_User $user
 */
$this->layout( 'layouts/plugin' );
?>

<div>
    <div>
        <dt-copy-text value="<?php echo esc_url( $magic_link ); ?>"></dt-copy-text>
    </div>

    <dt-home-hidden-app-menu id="appGrid" app-data='<?php echo esc_js( $data ); ?>'
                             app-url='<?php echo esc_url( $app_url ); ?>'>
        <!-- Add more app icons as needed -->
    </dt-home-hidden-app-menu>
    <div>
		<?php
        // phpcs:ignore
        echo $this->section( 'content' ) ?>
    </div>

    <b>
        <?php esc_html_e( 'Name:', 'dt-home' ); ?> <?php echo esc_html( $user->user_nicename ); ?>
    </b>

    <a href="<?php echo esc_url( $subpage_url ); ?>">
        <?php esc_html_e( 'Visit subpage', 'dt-home' ); ?>
    </a>

    <dt-home-footer></dt-home-footer>
</div>
