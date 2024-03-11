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
                    <span style="float:right;">
                        <a href="admin.php?page=dt_home&tab=app&action=create" class="button float-right"><i
                                class="fa fa-plus"></i> Add App</a>
                    </span>

                <br><br>

                <table class="widefat striped" style="border-collapse: collapse; width: 100%;">
                    <thead>
                    <tr>
                        <th style="border: 1px solid #ddd;">Name</th>
                        <th style="border: 1px solid #ddd;">Type</th>
                        <th style="border: 1px solid #ddd;">Icon</th>
                        <th style="border: 1px solid #ddd;">Slug</th>
                        <th style="border: 1px solid #ddd;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $data as $app ) : ?>
                        <tr>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app['name'] ); ?></td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_html( $app['type'] ); ?></td>
                            <td style="border: 1px solid #ddd;">
                                <?php if ( !empty( $app['icon'] ) ) : ?>
                                    <img src="<?php echo esc_url( $app['icon'] ); ?>" alt="Icon"
                                         style="width: 50px; height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td style="border: 1px solid #ddd;"><?php echo esc_attr( $app['slug'] ); ?></td>

                            <td style="border: 1px solid #ddd;">
                                <a href="admin.php?page=dt_home&tab=app&action=up/<?php echo esc_attr( $app['id'] ); ?>">Up</a>|&nbsp;
                                <a href="admin.php?page=dt_home&tab=app&action=edit/<?php echo esc_attr( $app['id'] ); ?>">Edit</a>|&nbsp;

                                <?php if ( $app['is_hidden'] == 1 ) { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=unhide/<?php echo esc_attr( $app['id'] ); ?>">Unhide</a>|&nbsp;
                                <?php } else { ?>
                                    <a href="admin.php?page=dt_home&tab=app&action=hide/<?php echo esc_attr( $app['id'] ); ?>">Hide</a>|&nbsp;
                                <?php } ?>

                                <a href="admin.php?page=dt_home&tab=app&action=down/<?php echo esc_attr( $app['id'] ); ?>">Down</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php $this->start( 'right' ) ?>

<!-- Add some content to the right side -->

<?php $this->stop() ?>
