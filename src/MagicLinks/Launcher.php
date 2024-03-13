<?php

namespace DT\Home\MagicLinks;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Support\Str;
use DT_Magic_Url_Base;
use function DT\Home\container;
use const DT\Home\Kucrut\Vite\VITE_CLIENT_SCRIPT_HANDLE;


/**
 * Class StarterMagicApp
 *
 * Represents the Starter Magic App for handling magic links.
 */
class Launcher extends DT_Magic_Url_Base {

	/**
	 * Initializes the value of the page title.
	 *
	 * The page title is used to display the title of the web page in the browser's title bar or tab.
	 *
	 * @var string $page_title The value of the page title.
	 */
	public $page_title = 'DT Home';

	/**
	 * Initializes the value of the page description.
	 *
	 * The page description is used to provide a brief summary or description of the web page's content.
	 *
	 * @var string $page_description The value of the page description.
	 */
	public $page_description = 'DT home screen.';

	/**
	 * Initializes the value of the root directory.
	 *
	 * The root directory is used as a reference point for other directories and files within the magic app.
	 *
	 * @var string $root The value of the root directory.
	 */
	public $root = 'home';

	/**
	 * Initializes the value of the type.
	 *
	 * The type specifies the type of the application, it represents the second part of the magic path.
	 *
	 * @var string $type The value of the type.
	 */
	public $type = 'launcher';


	/**
	 * Initializes the value of the post type.
	 *
	 * The post type determines the post type that the magic link type is associated with.
	 *
	 * @var string $post_type The value of the post type.
	 */
	public $post_type = 'user';

	/**
	 * @var bool $show_bulk_send Flag indicating whether the bulk send functionality should be shown or not.
	 */
	public $show_bulk_send = false;

	/**
	 * @var bool $show_app_tile Flag indicating whether the app tile should be shown or not.
	 */
	public $show_app_tile = false;

	/**
	 * @var array $meta Used to store meta information or key-value pairs.
	 */
	public $meta = [];

	/**
	 * Initializes the value of the meta key.
	 *
	 * The meta key is used to store meta information or key-value pairs.
	 *
	 * @var string $meta_key The value of the meta key.
	 */
	private $meta_key = '';


	/**
	 * Constructor for the class.
	 *
	 * Initializes the object and sets up the metadata and filters for the magic link processing.
	 */
	public function __construct() {
		/**
		 * Specify metadata structure, specific to the processing of current
		 * magic link type.
		 *
		 * - meta:              Magic link plugin related data.
		 *      - app_type:     Flag indicating type to be processed by magic link plugin.
		 *      - post_type     Magic link type post type.
		 *      - contacts_only:    Boolean flag indicating how magic link type user assignments are to be handled within magic link plugin.
		 *                          If True, lookup field to be provided within plugin for contacts only searching.
		 *                          If false, Dropdown option to be provided for user, team or group selection.
		 *      - fields:       List of fields to be displayed within magic link frontend form.
		 */
		$this->meta = [
			'app_type'      => 'magic_link',
			'post_type'     => $this->post_type,
			'contacts_only' => false,
			'fields'        => [
				[
					'id'    => 'name',
					'label' => 'Name',
				],
			],
		];

		$this->meta_key = $this->root . '_' . $this->type . '_magic_key';

		/**
		 * We aren't using the magic link for routing, so we need to add the current route to the list of allowed actions.
		 */
		$this->type_actions[ Str::afterLast( container()->make( Request::class )->getUri(), '/' ) ] = 'Current Route';

		parent::__construct();

		/**
		 * user_app and module section
		 */
		add_filter( 'dt_settings_apps_list', [ $this, 'dt_settings_apps_list' ], 10, 1 );

		/**
		 * tests if other URL
		 */
		$url = dt_get_url_path();
		if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
			return;
		}
		/**
		 * tests magic link parts are registered and have valid elements
		 */
		if ( ! $this->check_parts_match() ) {
			return;
		}

		// load if valid url
		add_filter( 'user_has_cap', [ $this, 'user_has_cap' ], 100, 3 );
		add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
		add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );

		add_filter( 'dt_allow_non_login_access', function () {
			// allows non-logged in visit
			return false;
		}, 100, 1 );
	}

	/**
	 * Determines if the given asset handle is allowed.
	 *
	 * This method checks if the provided asset handle is contained in the list of allowed handles.
	 * Allows the Template script file and the Vite client script file for dev use.
	 *
	 * @param string $asset_handle The asset handle to check.
	 *
	 * @return bool True if the asset handle is allowed, false otherwise.
	 */
	public function should_allow_asset( $asset_handle ) {
		return Str::contains( $asset_handle, [
			'dt_home',
			VITE_CLIENT_SCRIPT_HANDLE
		] );
	}

	/**
	 * Returns the list of allowed JavaScript assets from the registered WordPress scripts.
	 *
	 * @param array $allowed_js The list of initially allowed JavaScript assets.
	 *
	 * @return array The filtered list of allowed JavaScript assets from the registered WordPress scripts.
	 */
	public function dt_magic_url_base_allowed_js( $allowed_js ) {
		global $wp_scripts;
		$allowed_js = array_filter( array_keys( $wp_scripts->registered ), [ $this, 'should_allow_asset' ] );

		return $allowed_js;
	}

	/**
	 * Returns the list of allowed CSS assets from the registered WordPress styles.
	 *
	 * @param array $allowed_css The list of initially allowed CSS assets.
	 *
	 * @return array The filtered list of allowed CSS assets from the registered WordPress styles.
	 */
	public function dt_magic_url_base_allowed_css( $allowed_css ) {
		global $wp_styles;
		$allowed_css = array_filter( array_keys( $wp_styles->registered ), [ $this, 'should_allow_asset' ] );

		return $allowed_css;
	}

	/**
	 * Builds magic link type settings payload:
	 * - key:               Unique magic link type key; which is usually composed of root, type and _magic_key suffix.
	 * - url_base:          URL path information to map with parent magic link type.
	 * - label:             Magic link type name.
	 * - description:       Magic link type description.
	 * - settings_display:  Boolean flag which determines if magic link type is to be listed within frontend user profile settings.
	 *
	 * @param $apps_list
	 *
	 * @return mixed
	 */
	public function dt_settings_apps_list( $apps_list ) {
		$apps_list[ $this->meta_key ] = [
			'key'              => $this->meta_key,
			'url_base'         => $this->root . '/' . $this->type,
			'label'            => $this->page_title,
			'description'      => $this->page_description,
			'settings_display' => true,
		];

		return $apps_list;
	}

	/**
	 * Make sure the user can do everything we need them to do during this request.
	 *
	 * @param array $allcaps Existing capabilities for the user
	 * @param string $caps Capabilities provided by map_meta_cap()
	 * @param array $args Arguments for current_user_can()
	 *
	 * @return array
	 * @see WP_User::has_cap() in wp-includes/capabilities.php
	 */
	public function user_has_cap( $allcaps, $caps, $args ) {
		$allcaps['view_any_contacts'] = true;
		$allcaps['access_contacts']   = true;

		return $allcaps;
	}
}
