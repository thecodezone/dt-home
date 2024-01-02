<?php

namespace DT\Launcher\MagicLinks;

use DT_Magic_Url_Base;


/**
 * Class StarterMagicApp
 *
 * Represents the Starter Magic App for handling magic links.
 */
class App extends DT_Magic_Url_Base {

	/**
	 * Initializes the value of the page title.
	 *
	 * The page title is used to display the title of the web page in the browser's title bar or tab.
	 *
	 * @var string $page_title The value of the page title.
	 */
	public $page_title = 'DT Launcher';

	/**
	 * Initializes the value of the page description.
	 *
	 * The page description is used to provide a brief summary or description of the web page's content.
	 *
	 * @var string $page_description The value of the page description.
	 */
	public $page_description = 'DT application launcher.';

	/**
	 * Initializes the value of the root directory.
	 *
	 * The root directory is used as a reference point for other directories and files within the magic app.
	 *
	 * @var string $root The value of the root directory.
	 */
	public $root = 'launcher';

	/**
	 * Initializes the value of the type.
	 *
	 * The type specifies the type of the application, it represents the second part of the magic path.
	 *
	 * @var string $type The value of the type.
	 */
	public $type = 'app';


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
	 * A list of actions that are allowed for the magic link type.
	 * Routes to actions not defined here will be blocked.
	 *
	 * @var array
	 */
	public $type_actions = [
		"subpage" => "subpage",
	];

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
		add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
		add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
	}

	/**
	 * Filter the list of allowed JavaScript files for the dt_magic_url_base_allowed_js function.
	 *
	 * This filter allows plugins and themes to add or remove JavaScript files that are considered
	 * allowed for the dt_magic_url_base_allowed_js function.
	 *
	 * @param array $allowed_js An array of JavaScript file paths that are allowed.
	 *
	 * @return array The modified array of allowed JavaScript file paths.
	 */
	public function dt_magic_url_base_allowed_js( $allowed_js ) {
		// @todo add or remove js files with this filter
		return $allowed_js;
	}

	/**
	 * Filter the list of allowed CSS files for the dt_magic_url_base_allowed_css function.
	 *
	 * This filter allows plugins and themes to add or remove CSS files that are considered
	 * allowed for the dt_magic_url_base_allowed_css function.
	 *
	 * @param array $allowed_css An array of CSS file paths that are allowed.
	 *
	 * @return array The modified array of allowed CSS file paths.
	 */
	public function dt_magic_url_base_allowed_css( $allowed_css ) {
		// @todo add or remove js files with this filter
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
}
