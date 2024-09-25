<?php
/**
 * General settings page
 *
 * @var string $tab
 * @var string $link
 * @var string $page_title
 * @var string $dt_home_require_login
 * @var string $dt_home_reset_apps
 * @var string $dt_home_button_color
 * @var string $dt_home_show_in_menu
 * @var string $dt_home_file_upload
 */
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post" action="admin.php?page=dt_home&tab=general">
	<?php wp_nonce_field( 'dt_admin_form_nonce' ); ?>
	<div class="error-message" style="display:none;"></div>
	<table class="widefat striped">
        <thead>
        <tr>
            <th colspan="3"> <?php esc_html_e( 'General Settings', 'dt-home' ); ?></th>
        </thead>
        <tbody>
		<tr>
			<td>
				<label for="require_user">
					<input type="checkbox" id="dt_home_require_login"
							name="dt_home_require_login" <?php checked( $dt_home_require_login ); ?>
					>
					<?php esc_html_e( 'Require users to login to access the home screen magic link?', 'dt-home' ); ?>
				</label>
			</td>

		</tr>
		<tr>
			<td>
				<label for="reset_app">
					<input type="checkbox" id="dt_home_reset_apps"
							name="dt_home_reset_apps" <?php checked( $dt_home_reset_apps ); ?>
					>
					<?php esc_html_e( 'Allow users to reset their apps?', 'dt-home' ); ?>
				</label>
			</td>
		</tr>
        <tr>
            <td>
                <label for="reset_app">
                    <input type="checkbox" id="dt_home_show_in_menu"
                           name="dt_home_show_in_menu" <?php checked( $dt_home_show_in_menu ); ?>>
                    <?php esc_html_e( 'Add "Apps" link to Disciple.Tools main menu?', 'dt-home' ); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td>
            <label for="dt_home_button_color" class="color-picker-lable">
                <input type="color" id="dt_home_button_color" class="color-picker"
                       name="dt_home_button_color" value="<?php echo esc_attr( $dt_home_button_color ); ?>"
                >
                <?php esc_html_e( 'Customize Add Button Color?', 'dt-home' ); ?>
            </label>
            </td>
        </tr>
        </tbody>
	</table>
    <br>
    <table class="widefat striped">
        <thead>
        <tr>
            <th colspan="3"> <?php esc_html_e( 'Custom Logo', 'dt-home' ); ?></th>
        </tr>
        </thead>
        <tbody>
          <tr>
              <td>
                  <table class="widefat striped">
                      <thead>
                      <tr>
                          <td><?php esc_html_e( 'Image', 'dt-home' ); ?></td>
                          <td><?php esc_html_e( 'Image link (must be https)', 'dt-home' ); ?> </td>
                          <td></td>
                          <td></td>
                      </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td style="background-color:#3f729b" id="image_preview"><img height="22px" src="<?php echo esc_url( $dt_home_file_upload ); ?>"></td>
                              <td><input type="text" id="dt_home_file_upload" name="dt_home_file_upload" value="<?php echo esc_url( $dt_home_file_upload ); ?>" pattern=".*\S+.*" title="<?php esc_attr_e( 'The name cannot be empty or just whitespace.', 'dt-home' ); ?>"></td>
                              <td></td>
                              <td><button class="button file-upload-display-uploader" id="upload_image_button"  style="margin-left:1%"><?php esc_html_e('Upload', 'dt-home' ); ?></button></td>
                          </tr>
                      </tbody>
                  </table>
              </td>
          </tr>
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td>
                <button type="submit" id="ml_email_main_col_update_but" class="button float-right">
                    <?php esc_html_e( 'Update', 'dt-home' ); ?>
                </button>
            </td>
        </tr>
    </table>
</form>

<?php $this->start( 'right' ); ?>

<!-- Add some content to the right side -->

<?php $this->stop(); ?>
