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
 */
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post" action="admin.php?page=dt_home&tab=general">
	<?php wp_nonce_field( 'dt_admin_form_nonce' ); ?>
	<div class="error-message" style="display:none;"></div>
	<table>
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
            <label for="dt_home_button_color">
                <input type="color" id="dt_home_button_color" class="color-picker"
                       name="dt_home_button_color" value="<?php echo esc_attr( $dt_home_button_color ); ?>"
                >
                <?php esc_html_e( 'Customize Add Button Color?', 'dt-home' ); ?>
            </label>
            </td>
        </tr>
		<!--For giving some space between the field and the button.-->
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
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
