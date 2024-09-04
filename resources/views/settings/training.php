<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post">
	<?php wp_nonce_field( 'dt_admin_form_nonce' ) ?>

    <!-- Add a form -->
</form>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                    <span style="float:right;">
                        <a href="admin.php?page=dt_home&tab=training&action=create" class="button float-right"><i
                                class="fa fa-plus"></i> <?php echo esc_html_e( 'Add Training' ); ?></a>
                    </span>

                <br><br>

                <table class="widefat striped" style="border-collapse: collapse; width: 110%;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Name' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Embed Videos' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Anchor' ); ?></th>
                        <th style="border: 1px solid #ddd;min-width: 100%"><?php echo esc_html_e( 'Action' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $data as $training ) : ?>
                        <tr>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $training['name'] ); ?></td>
                            <td style="border: 1px solid #ddd;"><?php echo stripslashes( $training['embed_video'] ); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <?php echo esc_html( $training['anchor'] ); ?>
                            </td>

                            <td style="border: 1px solid #ddd;">
                                <a href="admin.php?page=dt_home&tab=training&action=up/<?php echo esc_attr( $training['id'] ); ?>"><?php echo esc_html_e( 'Up' ); ?></a>&nbsp;|&nbsp;<a
                                    href="admin.php?page=dt_home&tab=training&action=edit/<?php echo esc_attr( $training['id'] ); ?>"><?php echo esc_html_e( 'Edit' ); ?></a>&nbsp;|&nbsp;<a
                                    href="#"
                                    onclick="confirmDelete(<?php echo esc_attr( $training['id'] ); ?>)"><?php echo esc_html_e( 'Delete' ); ?></a>&nbsp;|&nbsp;<a
                                    href="admin.php?page=dt_home&tab=training&action=down/<?php echo esc_attr( $training['id'] ); ?>"><?php echo esc_html_e( 'Down' ); ?></a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(trainingId) {
        var confirmation = confirm("Are you sure you want to delete this entry?");
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = "admin.php?page=dt_home&tab=training&action=delete/" + trainingId;
        }
        // If the user cancels, do nothing
    }
</script>
<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
