<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form action="admin.php?page=dt_home&tab=training&action=edit/<?php echo esc_attr( $existing_data['id'] ); ?>"
      method="post" enctype="multipart/form-data">

    <?php wp_nonce_field( 'dt_admin_form_nonce' ) ?>
    <table class="widefat striped" id="ml_email_main_col_config">
        <thead>
        <tr>
            <th>Training Videos</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="vertical-align: middle;">Name </td>
            <td colspan="3">
                <input style="min-width: 100%;" type="text" name="name" id="name" class="form-control"
                       value="<?php echo esc_attr( $existing_data['name'] ); ?>" pattern=".*\S+.*" title="The name cannot be empty or just whitespace." required>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Embed Video </td>
            <td colspan="3">
                <textarea style="min-width: 100%;" class="form-control" name="embed_video" id="embed_video"
                          oninput="this.setCustomValidity(this.value.trim() === '' ? 'The video embed cannot be empty or just whitespace.' : '')"
                          required><?php echo stripslashes( esc_html( $existing_data['embed_video'] ) ); ?>
                </textarea>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Anchor </td>
            <td colspan="2">
                <input style="min-width: 100%;" class="form-control" type="text" name="anchor" id="anchor"
                       value="<?php echo esc_attr( $existing_data['anchor'] ); ?>" pattern=".*\S+.*" title="The name cannot be empty or just whitespace." required/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Sort </td>
            <td colspan="3">
                <input style="min-width: 100%;" type="number" name="sort" id="sort" class="form-control"
                       value="<?php echo esc_attr( $existing_data['sort'] ); ?>" required>
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span style="float:right;">
        <input type="hidden" name="edit_id" value="<?php echo esc_attr( $existing_data['id'] ); ?>">
        <a href="admin.php?page=dt_home&tab=training"
           class="button float-right"><?php esc_html_e( 'Cancel', 'disciple_tools' ) ?></a>
        <button type="submit" name="submit" id="submit"
                class="button float-right"><?php esc_html_e( 'Update', 'disciple_tools' ) ?></button>
    </span>
</form>

<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
