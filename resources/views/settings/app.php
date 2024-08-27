<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post">
    <?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>

    <!-- Add a form -->
</form>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                    <span style="float:left;">
                        <a href="admin.php?page=dt_home&tab=app&action=available_app" class="button float-right"><i
                                class="fa fa-plus"></i><?php echo esc_html_e( 'Available Apps' ); ?> </a>
                        </span>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                          <span style="float:right;">
                         <a href="admin.php?page=dt_home&tab=app&action=create" class="button float-right"><i
                                 class="fa fa-plus"></i><?php echo esc_html_e( 'Add App' ); ?> </a>
                    </span>
                <br><br>
                <table class="widefat striped" style="border-collapse: collapse; width: 100%;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Name' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Type' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Icon' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Slug' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php echo esc_html_e( 'Action' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $data as $app ) : ?>
                        <tr>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app['name'] ); ?></td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app['type'] ); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <?php if ( !empty( $app['icon'] ) ) : ?>
                                    <?php if ( filter_var( $app['icon'], FILTER_VALIDATE_URL ) || strpos( $app['icon'], '/wp-content/' ) === 0 ) : ?>
                                        <img src="<?php echo esc_url( $app['icon'] ); ?>" alt="Icon"
                                             style="width: 50px; height: 50px;">
                                    <?php elseif ( preg_match( '/^mdi\smdi-/', $app['icon'] ) ) : ?>
                                        <i class="<?php echo esc_attr( $app['icon'] ); ?>" style="font-size: 50px;"></i>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_attr( $app['slug'] ); ?></td>

                            <td style="border: 1px solid #ddd;">
                                <a href="admin.php?page=dt_home&tab=app&action=up/<?php echo esc_attr( $app['slug'] ); ?>"><?php echo esc_html_e( 'Up' ); ?></a>&nbsp;
                                |&nbsp;
                                <?php if ( $app['is_hidden'] == 1 ) { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=unhide/<?php echo esc_attr( $app['slug'] ); ?>"><?php echo esc_html_e( 'Unhide' ); ?></a>&nbsp;|&nbsp;
                                <?php } else { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=hide/<?php echo esc_attr( $app['slug'] ); ?>"><?php echo esc_html_e( 'Hide' ); ?></a>&nbsp;|&nbsp;
                                <?php } ?>
                                <a href="admin.php?page=dt_home&tab=app&action=edit/<?php echo esc_attr( $app['slug'] ); ?>"><?php echo esc_html_e( 'Edit' ); ?></a>&nbsp;
                                |&nbsp;
                                <a href="admin.php?page=dt_home&tab=app&action=down/<?php echo esc_attr( $app['slug'] ); ?>"><?php echo esc_html_e( 'Down' ); ?></a>&nbsp;
                                <?php if ( $app['type'] != 'custom' ) { ?>
                                    |&nbsp;
                                    <a href="#"
                                       onclick="deleteApp('<?php echo esc_attr( $app['slug'] ); ?>')"
                                       class="delete-apps">
                                        <?php echo esc_html_e( 'Delete' ); ?>
                                    </a>
                                <?php  }else{ ?>
                                    |&nbsp;
                                    <a href="#" onclick="softdelete('<?php echo esc_attr( $app['slug'] ); ?>')"
                                       class="delete-apps">
                                        <?php echo esc_html_e( 'Delete' ); ?>
                                    </a>
                                <?php     }
                               ?>
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
    function deleteApp(slug) {
        var confirmation = confirm(<?php echo json_encode( __( 'Are you sure you want to delete this app?', 'disciple_tools' ) ); ?>);
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = "admin.php?page=dt_home&tab=app&action=delete/" + slug;
        }
        // If the user cancels, do nothing
    }
    function softdelete(slug) {
        var confirmation = confirm(<?php echo json_encode( __( 'Are you sure you want to delete this app?', 'disciple_tools' ) ); ?>);
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = "admin.php?page=dt_home&tab=app&action=softdelete/" + slug;
        }
        // If the user cancels, do nothing
    }
</script>
<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
