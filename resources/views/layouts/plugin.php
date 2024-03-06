<div class="cloak">
    <div class="container">
        <div>
            <div class="section__inner">
                <?php $user = wp_get_current_user();
                ?>

                <?php if ( $user->has_cap( 'access_disciple_tools' ) ): ?>
                    <?php
                    $magic_url = DT\Home\magic_url();
                    $training = $magic_url . '/training';
                    $logout = $magic_url . '/logout';
                    $home = '/home';
                    $menu_items = json_encode([
                        [ 'label' => __( 'Apps', 'dt_home' ), 'href' => $home ],
                        [ 'label' => __( 'Training', 'dt_home' ), 'href' => $training ],
                        [ 'label' => __( 'Log Out', 'dt_home' ), 'href' => $logout ],
                    ]);
                    ?>
                    <menu-component menuItems='<?php echo $menu_items; ?>'></menu-component>

                <?php endif; ?>
                <?php echo $this->section( 'content' ) ?>
            </div>
        </div>
    </div>
</div>

