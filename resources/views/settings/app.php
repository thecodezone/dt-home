<?php
$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

<form method="post">
    <?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>

    <!-- Add a form -->
</form>
<!-- Custom Popup Model-->
<div id="overlay" class="overlay"></div>
<div id="exportPopup" class="popup-model" style="display: none" data-apps='<?php echo json_encode( $data ); ?>'
     data-site-domain="<?php echo esc_url( get_site_url() ); ?>">
    <div class="popup-content">
        <div class="popup-header">
            <h2 style="float: left">
                <?php esc_html_e( 'Copy the Selected Apps', 'dt-home' ); ?></h2>
            <span class="close close-button" style="float: right">&times;</span>
        </div>
        <div class="popup-body">
            <textarea id="exportTextarea" rows="10" class="form-control text-area" readonly></textarea>
        </div>
        <div class="popup-footer">
            <button id="copyButton" class="button"><i class="fa fa-copy"></i>&nbsp;
                <?php esc_html_e( 'Copy', 'dt-home' ); ?></button>
            &nbsp;&nbsp;&nbsp;
            <button class="button close-button"><?php esc_html_e( 'Close', 'dt-home' ); ?></button>
        </div>
    </div>
</div>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <span style="float:left;">
                    <a href="admin.php?page=dt_home&tab=app&action=available_app" class="button float-right">
                        <i class="fas fa-trash-restore"></i> <?php esc_html_e( 'Available Apps', 'dt-home' ); ?>
                    </a>
                </span>
                <div class="apps-btn">
                <span>
                    <button class="button" id="exportButton" disabled>
                       <i class="fas fa-file-export"></i> <?php esc_html_e( 'Export Apps', 'dt-home' ); ?>
                    </button>
                </span>
                    &nbsp;&nbsp;&nbsp;
                    <span>
                    <a href="admin.php?page=dt_home&tab=app&action=create" class="button">
                        <i class="fa fa-plus"></i>
                        <?php esc_html_e( 'Add App', 'dt-home' ); ?>
                    </a>
                </span>
                </div>
                <br><br>
                <table class="widefat striped" style="border-collapse: collapse; width: 100%;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #ddd;">
                            <input type="checkbox" id="select_all_checkbox" class="select-all custom-checkbox">
                        </th>
                        <th style="border: 1px solid #ddd;"><?php esc_html_e( 'Name', 'dt-home' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php esc_html_e( 'Type', 'dt-home' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php esc_html_e( 'Icon', 'dt-home' ); ?></th>
                        <th style="border: 1px solid #ddd;"><?php esc_html_e( 'Slug', 'dt-home' ); ?></th>
                        <th style="border: 1px solid #ddd; min-width: 100%;"><?php esc_html_e( 'Action', 'dt-home' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $data as $app ) : ?>
                        <?php
                        $app_type_label_prefix = '';
                        switch ( $app['creation_type'] ?? '' ) {
                            case 'code':
                                $app_type_label_prefix = 'Code / ';
                                break;
                            case 'custom':
                                $app_type_label_prefix = 'Custom / ';
                                break;
                            default:
                                break;
                        }
                        $app_type_label_prefix .= $app['type'];
                        ?>
                        <tr>
                            <td style="border: 1px solid #ddd;"><input type="checkbox"
                                                                       value="<?php echo esc_attr( $app['slug'] ); ?>"
                                                                       class="app-checkbox custom-checkbox">
                            </td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app['name'] ); ?></td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app_type_label_prefix ); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <?php if ( !empty( $app['icon'] ) ) : ?>
                                    <?php if ( filter_var( $app['icon'], FILTER_VALIDATE_URL ) || strpos( $app['icon'], '/wp-content/' ) === 0 ) : ?>
                                        <img src="<?php echo esc_url( $app['icon'] ); ?>"
                                             alt="<?php esc_attr_e( 'Icon', 'dt-home' ); ?>"
                                             style="width: 50px; height: 50px;">
                                    <?php elseif ( preg_match( '/^mdi\smdi-/', $app['icon'] ) ) : ?>
                                        <i class="<?php echo esc_attr( $app['icon'] ); ?>" style="font-size: 50px;"></i>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_attr( $app['slug'] ); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <a href="admin.php?page=dt_home&tab=app&action=up/<?php echo esc_attr( $app['slug'] ); ?>"><?php esc_html_e( 'Up', 'dt-home' ); ?></a>&nbsp;|&nbsp;
                                <?php if ( $app['is_hidden'] == 1 ) { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=unhide/<?php echo esc_attr( $app['slug'] ); ?>"><?php esc_html_e( 'Unhide', 'dt-home' ); ?></a>&nbsp;|&nbsp;
                                <?php } else { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=hide/<?php echo esc_attr( $app['slug'] ); ?>"><?php esc_html_e( 'Hide', 'dt-home' ); ?></a>&nbsp;|&nbsp;
                                <?php } ?>
                                <a href="admin.php?page=dt_home&tab=app&action=edit/<?php echo esc_attr( $app['slug'] ); ?>"><?php esc_html_e( 'Edit', 'dt-home' ); ?></a>&nbsp;|&nbsp;
                                <a href="admin.php?page=dt_home&tab=app&action=down/<?php echo esc_attr( $app['slug'] ); ?>"><?php esc_html_e( 'Down', 'dt-home' ); ?></a>&nbsp;|&nbsp;
                                <a href="javascript:void(0)"
                                   onclick="copyApp('<?php echo esc_attr( $app['slug'] ); ?>', this)"><?php esc_html_e( 'Copy', 'dt-home' ); ?></a>&nbsp;
                                <?php if ( !isset( $app['creation_type'] ) || ( $app['creation_type'] != 'code' ) ) { ?>
                                    |&nbsp;
                                    <a href="#" onclick="deleteApp('<?php echo esc_attr( $app['slug'] ); ?>')"
                                       class="delete-apps">
                                        <?php esc_html_e( 'Delete', 'dt-home' ); ?>
                                    </a>
                                <?php } else { ?>
                                    |&nbsp;
                                    <a href="#" onclick="softdelete('<?php echo esc_attr( $app['slug'] ); ?>')"
                                       class="delete-apps">
                                        <?php esc_html_e( 'Delete', 'dt-home' ); ?>
                                    </a>
                                <?php } ?>
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
        var confirmation = confirm(<?php echo json_encode( __( 'Are you sure you want to delete this app?', 'dt-home' ) ); ?>)
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = 'admin.php?page=dt_home&tab=app&action=delete/' + slug
        }
        // If the user cancels, do nothing
    }

    function softdelete(slug) {
        var confirmation = confirm(<?php echo json_encode( __( 'Are you sure you want to delete this app?', 'dt-home' ) ); ?>)
        if (confirmation) {
            // If the user confirms, redirect to the delete URL
            window.location.href = 'admin.php?page=dt_home&tab=app&action=softdelete/' + slug
        }
        // If the user cancels, do nothing
    }
</script>
<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
