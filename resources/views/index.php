<?php
/**
 * @var string $magic_link
 * @var string $data
 * @var string $app_url
 * @var string $subpage_url
 * @var WP_User $user
 * @var string $reset_apps
 * @var string $button_color
 */
$this->layout( 'layouts/plugin' );


use function DT\Home\magic_url;
use function DT\Home\plugin_url;

$full_link = magic_url();
?>

<header id="app-header">
    <dt-home-tooltip translations='
        <?php
    echo wp_json_encode(
        [
            'helpText' => __( 'Copy this link and share it with people you are coaching', 'dt-home' ),
        ]
    )
		?>
        '
    ></dt-home-tooltip>
    <dt-copy-text value="<?php echo esc_url( $magic_link ); ?>"></dt-copy-text>
    <sp-button id="trigger" class="qr-code-button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-qr-code-scan qr-svg-image"
             viewBox="0 0 16 16">
            <path
                d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
            <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
            <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
            <path
                d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
            <path d="M12 9h2V8h-2z" />
        </svg>
    </sp-button>
    <sp-overlay trigger="trigger@click" type="modal">
        <sp-dialog-wrapper dismissable underlay>
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&color=323a68&data=
            <?php echo esc_url( $full_link ) ?>"
                title="<?php echo esc_url( $full_link ) ?>" alt="
            <?php echo esc_url( $full_link ) ?>"
                style="width:100%;" />
        </sp-dialog-wrapper>
    </sp-overlay>

</header>

<dt-home-app-grid id="appGrid" app-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                  app-url='<?php echo esc_url( $app_url ); ?>'>
    <!-- Add more app icons as needed -->
</dt-home-app-grid>

<div>
    <?php
    // phpcs:ignore
    echo $this->section('content') ?>
</div>

<?php $this->start( 'footer' ); ?>

<dt-home-footer id="hiddenApps"
                translations='<?php echo wp_json_encode([
                    "hiddenAppsLabel" => __( "Hidden Apps", 'dt-home' ),
                    "buttonLabel" => __( "Ok", 'dt-home' )
                ]) ?>'
                hidden-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                app-url-unhide='<?php echo esc_url( $app_url ); ?>'
                reset-apps='<?php echo esc_attr( $reset_apps ); ?>'
                button-color='<?php echo esc_attr( $button_color ); ?>'>
</dt-home-footer>

<?php $this->stop(); ?>

