<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post" action="admin.php?page=dt_home&tab=general">
    <?php wp_nonce_field( 'dt_admin_form_nonce' ) ?>
    <div class="error-message" style="display:none;"></div>
    <table>
        <tr>
            <td>
                <label for="require_user">
                    <input type="checkbox" id="dt_home_require_login"
                           name="dt_home_require_login" <?php checked( $dt_home_require_login ); ?>
                    >
                    <?php esc_html_e( 'Require users to login to access the home screen?', 'dt_home' ); ?>
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
                    <?php esc_html_e( 'Update', 'dt_home' ); ?>
                </button>
            </td>
        </tr>
    </table>
</form>

<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
