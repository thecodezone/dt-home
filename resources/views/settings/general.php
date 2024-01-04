<?php

$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post" action="admin.php?page=dt_launcher&tab=general&action=update">
		<?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>
        <!-- Add a form -->
        <table>
            <tr>
                <td>
                    <label for="require_user">
                        <input type="checkbox" id="require_user" name="require_user"  value="1" <?php checked( get_option( 'is_user_logged_in', false ) ); ?>>
                        <?php $this->esc_html_e( 'Require users to login to access launcher' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" id="ml_email_main_col_update_but" class="button float-right">
                        <?php $this->esc_html_e( 'Update' ); ?>
                    </button>
                </td>
            </tr>
        </table>
    </form>

<?php $this->start( 'right' ) ?>

    <!-- Add some content to the right side -->

<?php $this->stop() ?>