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
        <img
            src="<?php echo plugin_url( 'resources/img/qr-code-svg.svg' ); ?>"
            class="qr-svg-image" />
    </sp-button>
    <sp-overlay trigger="trigger@click" type="modal">
        <sp-dialog-wrapper headline="Magic Link QR Code" dismissable underlay>
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&color=323a68&data=
            <?php echo esc_url( $full_link ) ?>"
                title="<?php echo esc_url( $full_link ) ?>" alt="
            <?php echo esc_url( $full_link ) ?>"
                style="width:100%;" />
            <br>
            <br>
            <sp-action-button
                style="float:right;"
                onClick="
                            this.dispatchEvent(
                                new Event('close', {
                                    bubbles: true,
                                    composed: true,
                                })
                            );
                        "
            >
                close
            </sp-action-button>
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

