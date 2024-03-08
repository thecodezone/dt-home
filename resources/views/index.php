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
    <dt-copy-text value="<?php echo esc_url( $magic_link ); ?>"></dt-copy-text>
</div>
<overlay-trigger placement="right">
    <sp-button slot="trigger" class="learn-more-text">
        <sp-icon-help></sp-icon-help> &nbsp;
        <?php esc_attr_e( 'learn more', 'dt_home' ); ?>
    </sp-button>

    <sp-tooltip slot="hover-content" open placement="right" class="spl-text">
        <!-- Dynamic content goes here -->
        <?php
        $text = "Copy this link and share it with people you are coaching.";
        $escaped_text = esc_attr( $text ); // If you need to ensure the text is safe for HTML attributes
        $newtext = wordwrap( $escaped_text, 40, "<br />\n" );
        echo $newtext;
        ?>
    </sp-tooltip>
</overlay-trigger>

<dt-home-app-grid id="appGrid" app-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                  app-url='<?php echo esc_url( $app_url ); ?>'>
    <!-- Add more app icons as needed -->
</dt-home-app-grid>
<div>
    <?php echo $this->section( 'content' ) ?>
</div>

<?php $this->start( 'footer' ) ?>

<dt-home-footer id="hiddenApps"
                translations='<?php echo wp_json_encode( [ "hiddenAppsLabel" => __( "Hidden Apps", 'dt_home' ) ] ) ?>'
                hidden-data='<?php echo esc_attr( htmlspecialchars( $data ) ); ?>'
                app-url-unhide='<?php echo esc_url( $app_url ); ?>'>
</dt-home-footer>

<?php $this->stop() ?>
