<?php
$user       = wp_get_current_user();
$magic_url  = DT\Home\magic_url();
$training   = $magic_url . '/training';
$logout     = $magic_url . '/logout';
$home       = '/home';
$dashboard  = '/';
$menu_items = [];

// Adding default menu items
$menu_items[] = [ 'label' => __( 'Apps', 'dt_home' ), 'href' => $home ];
$menu_items[] = [ 'label' => __( 'Training', 'dt_home' ), 'href' => $training ];
$menu_items[] = [ 'label' => __( 'Log Out', 'dt_home' ), 'href' => $logout ];

// Adding additional menu item based on user capability
if ( $user->has_cap( 'access_disciple_tools' ) ) {
	$menu_items[] = [ 'label' => __( 'Disciple.Tools', 'dt_home' ), 'href' => $dashboard ];
}
$menu_items_json = wp_json_encode( $menu_items );
?>

<sp-theme
        theme="spectrum"
        color="light"
        scale="medium"
>
    <div class="plugin cloak">
        <div class="plugin__main">
            <div class="container">

                <dt-home-menu menuItems='<?php echo $menu_items_json; ?>'></dt-home-menu>

				<?php echo $this->section( 'content' ) ?>
            </div>
        </div>

        <div class="plugin__footer">
            <div class="container">
				<?php echo $this->section( 'footer' ) ?>
            </div>
        </div>
    </div>
</sp-theme>
