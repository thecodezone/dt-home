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
<header id="app-header">
    <overlay-trigger placement="right">
        <div slot="trigger">
            <sp-icon-help></sp-icon-help>
        </div>

        <sp-tooltip slot="hover-content" open placement="right" class="spl-text">
            <?php echo wordwrap( esc_html__( "Copy this link and share it with people you are coaching.", "dt_home" ), 40, "<br />\n" ); ?>
        </sp-tooltip>
    </overlay-trigger>

    <dt-copy-text value="<?php echo esc_url( $magic_link ); ?>"></dt-copy-text>
</header>

<dt-home-app-grid id="appGrid" app-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                  app-url='<?php echo esc_url( $app_url ); ?>'>
    <!-- Add more app icons as needed -->
</dt-home-app-grid>

<div>
    <?php echo $this->section( 'content' ) ?>
</div>

<?php $this->start( 'footer' ) ?>

    <dt-home-footer id="hiddenApps"
                    translations='<?php echo wp_json_encode([
                        "hiddenAppsLabel" => esc_html__( "Hidden Apps", 'dt_home' ),
                        "buttonLabel" => esc_html__( "Ok", 'dt_home' )
                    ]) ?>'
                    hidden-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                    app-url-unhide='<?php echo esc_url( $app_url ); ?>'>
    </dt-home-footer>

<?php $this->stop() ?>
