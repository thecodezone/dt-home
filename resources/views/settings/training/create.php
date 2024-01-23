<?php
$this->layout('layouts/settings', compact('tab', 'link', 'page_title'))
?>

<form action="admin.php?page=dt_launcher&tab=training&action=create" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('dt_admin_form', 'dt_admin_form_nonce') ?>

    <table class="widefat striped" id="ml_email_main_col_config">
        <thead>
        <tr>
            <th>Training Video</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="vertical-align: middle;">Name [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" class="form-control" type="text" name="name" id="name" required/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Embed Video [&#63;]</td>
            <td colspan="2">
                <textarea style="min-width: 100%;" class="form-control" name="embed_video" id="embed_video"
                          required></textarea>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Anchor [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" class="form-control" type="text" name="anchor" id="anchor"
                       required/>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Sort [&#63;]</td>
            <td colspan="2">
                <input style="min-width: 100%;" type="number" name="sort" id="sort" required/>
            </td>
        </tr>
        </tbody>
    </table>

    <br>
    <span id="ml_email_main_col_update_msg" style="font-weight: bold; color: red;"></span>
    <span style="float:right;">
        <a href="admin.php?page=dt_launcher&tab=training"
           class="button float-right"><?php esc_html_e('Cancel', 'disciple_tools') ?></a>
        <button type="submit" id="ml_email_main_col_update_but"
                class="button float-right"><?php esc_html_e('Submit', 'disciple_tools') ?></button>
    </span>
</form>

<?php $this->start('right') ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
