<?php

namespace DT\Home\PostTypes;

use Disciple_Tools_Post_Type_Template;
use DT_Mapbox_API;
use DT_Module_Base;
use DT_Posts;
use stdClass;

/**
 * Define the StarterPostType class.
 */
class StarterPostType extends DT_Module_Base {
	/**
	 * The post type handle
	 *
	 * @var string
	 */
	public $post_type = 'starter_post_type';

	/**
	 * The module name for the post type.
	 *
	 * @var string $module The module name.
	 */
	public $module = 'starter_base';

	/**
	 * The singular display name for the post type.
	 *
	 * @var string $single_name The singular name.
	 */
	public $single_name = 'Starter';

	/**
	 * The plural display name for the post type.
	 *
	 * @var string $plural_name The plural name for the post type.
	 */
	public $plural_name = 'Starters';// scripts


	/**
	 * Constructor hooks.
	 */
	public function __construct() {
		parent::__construct();

		if ( ! self::check_enabled_and_prerequisites() ) {
			return;
		}


		//setup post type
		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 100 );
		add_filter( 'dt_set_roles_and_permissions', [ $this, 'dt_set_roles_and_permissions' ], 20, 1 ); //after contacts

		//setup tiles and fields
		add_filter( 'dt_custom_fields_settings', [ $this, 'dt_custom_fields_settings' ], 10, 2 );
		add_filter( 'dt_details_additional_tiles', [ $this, 'dt_details_additional_tiles' ], 10, 2 );
		add_action( 'dt_details_additional_section', [ $this, 'dt_details_additional_section' ], 20, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
		add_filter( 'dt_get_post_type_settings', [ $this, 'dt_get_post_type_settings' ], 20, 2 );

		// hooks
		add_action( 'post_connection_removed', [ $this, 'post_connection_removed' ], 10, 4 );
		add_action( 'post_connection_added', [ $this, 'post_connection_added' ], 10, 4 );
		add_filter( 'dt_post_update_fields', [ $this, 'dt_post_update_fields' ], 10, 3 );
		add_filter( 'dt_post_create_fields', [ $this, 'dt_post_create_fields' ], 10, 2 );
		add_action( 'dt_post_created', [ $this, 'dt_post_created' ], 10, 3 );
		add_action( 'dt_comment_created', [ $this, 'dt_comment_created' ], 10, 4 );

		//list
		add_filter( 'dt_user_list_filters', [ $this, 'dt_user_list_filters' ], 10, 2 );
		add_filter( 'dt_filter_access_permissions', [ $this, 'dt_filter_access_permissions' ], 20, 2 );
	}

	/**
	 * Setup user list filters
	 *
	 * @param $filters
	 * @param $post_type
	 *
	 * @return array
	 */
	public static function dt_user_list_filters( $filters, $post_type ): array {

		if ( $post_type === self::post_type() ) {
			$records_assigned_to_me_by_status_counts = self::count_records_assigned_to_me_by_status();
			$fields                                  = DT_Posts::get_post_field_settings( $post_type );
			/**
			 * Setup my filters
			 */
			$active_counts = [];
			$status_counts = [];
			$total_my      = 0;
			foreach ( $records_assigned_to_me_by_status_counts as $count ) {
				$total_my += $count['count'];
				dt_increment( $status_counts[ $count['status'] ], $count['count'] );
			}

			// add assigned to me tab
			$filters['tabs'][] = [
				'key'   => 'assigned_to_me',
				'label' => __( 'Assigned to me', 'cz-plugin' ),
				'count' => $total_my,
				'order' => 20
			];
			// add assigned to me filters
			$filters['filters'][] = [
				'ID'    => 'my_all',
				'tab'   => 'assigned_to_me',
				'name'  => __( 'All', 'cz-plugin' ),
				'query' => [
					'assigned_to' => [ 'me' ],
					'sort'        => 'status'
				],
				'count' => $total_my,
			];
			//add a filter for each status
			foreach ( $fields['status']['default'] as $status_key => $status_value ) {
				if ( isset( $status_counts[ $status_key ] ) ) {
					$filters['filters'][] = [
						'ID'    => 'my_' . $status_key,
						'tab'   => 'assigned_to_me',
						'name'  => $status_value['label'],
						'query' => [
							'assigned_to' => [ 'me' ],
							'status'      => [ $status_key ],
							'sort'        => '-post_date'
						],
						'count' => $status_counts[ $status_key ]
					];
				}
			}

			if ( DT_Posts::can_view_all( self::post_type() ) ) {
				$records_by_status_counts = self::count_records_by_status();
				$status_counts            = [];
				$total_all                = 0;
				foreach ( $records_by_status_counts as $count ) {
					$total_all += $count['count'];
					dt_increment( $status_counts[ $count['status'] ], $count['count'] );
				}

				// add by Status Tab
				$filters['tabs'][] = [
					'key'   => 'by_status',
					'label' => __( 'All By Status', 'cz-plugin' ),
					'count' => $total_all,
					'order' => 30
				];
				// add assigned to me filters
				$filters['filters'][] = [
					'ID'    => 'all_status',
					'tab'   => 'by_status',
					'name'  => __( 'All', 'cz-plugin' ),
					'query' => [
						'sort' => '-post_date'
					],
					'count' => $total_all
				];

				foreach ( $fields['status']['default'] as $status_key => $status_value ) {
					if ( isset( $status_counts[ $status_key ] ) ) {
						$filters['filters'][] = [
							'ID'    => 'all_' . $status_key,
							'tab'   => 'by_status',
							'name'  => $status_value['label'],
							'query' => [
								'status' => [ $status_key ],
								'sort'   => '-post_date'
							],
							'count' => $status_counts[ $status_key ]
						];
					}
				}
			}
		}

		return $filters;
	}

	/**
	 * Return the post type name
	 *
	 * @return string
	 */
	public static function post_type(): string {
		return 'starter_post_type';
	}

	/**
	 * Query the count of records assigned to current user by status
	 * @link https://github.com/DiscipleTools/Documentation/blob/master/Theme-Core/list-query.md
	 */
	private static function count_records_assigned_to_me_by_status(): array|object|null {
		global $wpdb;
		$post_type    = self::post_type();
		$current_user = get_current_user_id();

		$results = $wpdb->get_results( $wpdb->prepare( "
            SELECT status.meta_value as status, count(pm.post_id) as count
            FROM $wpdb->postmeta pm
            INNER JOIN $wpdb->posts a ON( a.ID = pm.post_id AND a.post_type = %s and a.post_status = 'publish' )
            INNER JOIN $wpdb->postmeta status ON ( status.post_id = pm.post_id AND status.meta_key = 'status' )
            WHERE pm.meta_key = 'assigned_to'
            AND pm.meta_value = CONCAT( 'user-', %s )
            GROUP BY status.meta_value
        ", $post_type, $current_user ), ARRAY_A );

		return $results;
	}

	/**
	 * Query to get the count of records by status
	 * @return array|object|stdClass[]|null
	 */
	private static function count_records_by_status(): array|object|null {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "
            SELECT status.meta_value as status, count(status.post_id) as count
            FROM $wpdb->postmeta status
            INNER JOIN $wpdb->posts a ON( a.ID = status.post_id AND a.post_type = %s and a.post_status = 'publish' )
            WHERE status.meta_key = 'status'
            GROUP BY status.meta_value
        ", self::post_type() ), ARRAY_A );

		return $results;
	}

	/**
	 * Permission filter to limit access to post type
	 *
	 * @param $permissions
	 * @param $post_type
	 *
	 * @return array|mixed
	 **/
	public static function dt_filter_access_permissions( $permissions, $post_type ) {
		if ( $post_type === self::post_type() ) {
			if ( DT_Posts::can_view_all( $post_type ) ) {
				$permissions = [];
			}
		}

		return $permissions;
	}

	/**
	 * Define the post type
	 *
	 * @return void
	 */
	public function after_setup_theme() {
		$this->single_name = __( 'Starter', 'cz-plugin' );
		$this->plural_name = __( 'Starters', 'cz-plugin' );

		if ( class_exists( 'Disciple_Tools_Post_Type_Template' ) ) {
			new Disciple_Tools_Post_Type_Template( $this->post_type, $this->single_name, $this->plural_name );
		}
	}

	/**
	 * Set the singular and plural translations for this post types settings
	 * The add_filter is set onto a higher priority than the one in Disciple_tools_Post_Type_Template
	 * so as to enable localisation changes. Otherwise the system translation passed in to the custom post type
	 * will prevail.
	 */
	public function dt_get_post_type_settings( $settings, $post_type ) {
		if ( $post_type === $this->post_type ) {
			$settings['label_singular'] = __( 'Starter', 'cz-plugin' );
			$settings['label_plural']   = __( 'Starters', 'cz-plugin' );
		}

		return $settings;
	}

	/**
	 * Set the roles and permissions for this post type
	 * @link https://github.com/DiscipleTools/Documentation/blob/master/Theme-Core/roles-permissions.md#rolesd
	 */
	public function dt_set_roles_and_permissions( $expected_roles ) {

		if ( ! isset( $expected_roles['my_starter_role'] ) ) {
			$expected_roles['my_starter_role'] = [

				'label'       => __( 'My Starter Role', 'cz-plugin' ),
				'description' => 'Does something Cool',
				'permissions' => [
					'access_contacts' => true,
					// @todo more capabilities
				]
			];
		}

		// if the user can access contact they also can access this post type
		foreach ( $expected_roles as $role => $role_value ) {
			if ( isset( $expected_roles[ $role ]['permissions']['access_contacts'] ) && $expected_roles[ $role ]['permissions']['access_contacts'] ) {
				$expected_roles[ $role ]['permissions'][ 'access_' . $this->post_type ] = true;
				$expected_roles[ $role ]['permissions'][ 'create_' . $this->post_type ] = true;
				$expected_roles[ $role ]['permissions'][ 'update_' . $this->post_type ] = true;
			}
		}

		if ( isset( $expected_roles['dt_admin'] ) ) {
			$expected_roles['dt_admin']['permissions'][ 'view_any_' . $this->post_type ]   = true;
			$expected_roles['dt_admin']['permissions'][ 'update_any_' . $this->post_type ] = true;
		}
		if ( isset( $expected_roles['administrator'] ) ) {
			$expected_roles['administrator']['permissions'][ 'view_any_' . $this->post_type ]   = true;
			$expected_roles['administrator']['permissions'][ 'update_any_' . $this->post_type ] = true;
			$expected_roles['administrator']['permissions'][ 'delete_any_' . $this->post_type ] = true;
		}

		return $expected_roles;
	}

	/**
	 * Set the custom fields for this post type
	 * @link https://github.com/DiscipleTools/Documentation/blob/master/Theme-Core/fields.md
	 */
	public function dt_custom_fields_settings( $fields, $post_type ) {
		if ( $post_type === $this->post_type ) {
			$fields['status']      = [
				'name'          => __( 'Status', 'cz-plugin' ),
				'description'   => __( 'Set the current status.', 'cz-plugin' ),
				'type'          => 'key_select',
				'default'       => [
					'inactive' => [
						'label'       => __( 'Inactive', 'cz-plugin' ),
						'description' => __( 'No longer active.', 'cz-plugin' ),
						'color'       => '#F43636'
					],
					'active'   => [
						'label'       => __( 'Active', 'cz-plugin' ),
						'description' => __( 'Is active.', 'cz-plugin' ),
						'color'       => '#4CAF50'
					],
				],
				'tile'          => 'status',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/status.svg',
				'default_color' => '#366184',
				'show_in_table' => 10,
			];
			$fields['assigned_to'] = [
				'name'          => __( 'Assigned To', 'cz-plugin' ),
				'description'   => __( 'Select the main person who is responsible for reporting on this record.', 'cz-plugin' ),
				'type'          => 'user_select',
				'default'       => '',
				'tile'          => 'status',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/assigned-to.svg',
				'show_in_table' => 16,
			];


			/**
			 * Common and recommended fields
			 */
			$fields['start_date']   = [
				'name'        => __( 'Start Date', 'cz-plugin' ),
				'description' => '',
				'type'        => 'date',
				'default'     => time(),
				'tile'        => 'details',
				'icon'        => get_template_directory_uri() . '/dt-assets/images/date-start.svg',
			];
			$fields['end_date']     = [
				'name'        => __( 'End Date', 'cz-plugin' ),
				'description' => '',
				'type'        => 'date',
				'default'     => '',
				'tile'        => 'details',
				'icon'        => get_template_directory_uri() . '/dt-assets/images/date-end.svg',
			];
			$fields['multi_select'] = [
				'name'           => __( 'Multi-Select', 'cz-plugin' ),
				'description'    => __( 'Multi Select Field', 'cz-plugin' ),
				'type'           => 'multi_select',
				'default'        => [
					'item_1' => [
						'label'       => __( 'Item 1', 'cz-plugin' ),
						'description' => __( 'Item 1.', 'cz-plugin' ),
					],
					'item_2' => [
						'label'       => __( 'Item 2', 'cz-plugin' ),
						'description' => __( 'Item 2.', 'cz-plugin' ),
					],
					'item_3' => [
						'label'       => __( 'Item 3', 'cz-plugin' ),
						'description' => __( 'Item 3.', 'cz-plugin' ),
					],
				],
				'tile'           => 'details',
				'in_create_form' => true,
				'icon'           => get_template_directory_uri() . '/dt-assets/images/languages.svg?v=2',
			];


			/**
			 * This adds location support to the post type
			 * It can be removed.
			 *
			 * location elements
			 */
			$fields['location_grid']      = [
				'name'           => __( 'Locations', 'cz-plugin' ),
				'description'    => __( 'The general location where this contact is located.', 'cz-plugin' ),
				'type'           => 'location',
				'mapbox'         => false,
				'in_create_form' => true,
				'tile'           => 'details',
				'icon'           => get_template_directory_uri() . '/dt-assets/images/location.svg',
			];
			$fields['location_grid_meta'] = [
				'name'        => __( 'Locations', 'cz-plugin' ),
				//system string does not need translation
				'description' => __( 'The general location where this record is located.', 'cz-plugin' ),
				'type'        => 'location_meta',
				'tile'        => 'details',
				'mapbox'      => false,
				'hidden'      => true,
				'icon'        => get_template_directory_uri() . '/dt-assets/images/location.svg?v=2',
			];
			$fields['contact_address']    = [
				'name'         => __( 'Address', 'cz-plugin' ),
				'icon'         => get_template_directory_uri() . '/dt-assets/images/house.svg',
				'type'         => 'communication_channel',
				'tile'         => 'details',
				'mapbox'       => false,
				'customizable' => false
			];

			if ( DT_Mapbox_API::get_key() ) {
				$fields['contact_address']['custom_display'] = true;
				$fields['contact_address']['mapbox']         = true;
				unset( $fields['contact_address']['tile'] );
				$fields['location_grid']['mapbox']      = true;
				$fields['location_grid_meta']['mapbox'] = true;
				$fields['location_grid']['hidden']      = true;
				$fields['location_grid_meta']['hidden'] = false;
			}
			// end locations

			/**
			 * This adds generational support to this post type. remove if not needed.
			 * generation and peer connection fields
			 */
			$fields['parents']  = [
				'name'          => __( 'Parents', 'cz-plugin' ),
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => $this->post_type,
				'p2p_direction' => 'from',
				'p2p_key'       => $this->post_type . '_to_' . $this->post_type,
				'tile'          => 'connections',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-parent.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-group.svg',
			];
			$fields['peers']    = [
				'name'          => __( 'Peers', 'cz-plugin' ),
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => $this->post_type,
				'p2p_direction' => 'any',
				'p2p_key'       => $this->post_type . '_to_peers',
				'tile'          => 'connections',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-peer.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-group.svg',
			];
			$fields['children'] = [
				'name'          => __( 'Children', 'cz-plugin' ),
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => $this->post_type,
				'p2p_direction' => 'to',
				'p2p_key'       => $this->post_type . '_to_' . $this->post_type,
				'tile'          => 'connections',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-child.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-group.svg',
			];
			// end generations

			/**
			 * This adds people groups support to this post type. remove if not needed.
			 * Connections to other post types
			 */
			$fields['peoplegroups'] = [
				'name'          => __( 'People Groups', 'cz-plugin' ),
				'description'   => __( 'The people groups connected to this record.', 'cz-plugin' ),
				'type'          => 'connection',
				'tile'          => 'details',
				'post_type'     => 'peoplegroups',
				'p2p_direction' => 'to',
				'p2p_key'       => $this->post_type . '_to_peoplegroups',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/people-group.svg',
			];

			$fields['contacts'] = [
				'name'          => __( 'Contacts', 'cz-plugin' ),
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => 'contacts',
				'p2p_direction' => 'to',
				'p2p_key'       => $this->post_type . '_to_contacts',
				'tile'          => 'status',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-type.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-contact.svg',
				'show_in_table' => 35
			];
		}

		/**
		 * This adds connection to contacts. remove if not needed.
		 */
		if ( $post_type === 'contacts' ) {
			$fields[ $this->post_type ] = [
				'name'          => $this->plural_name,
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => $this->post_type,
				'p2p_direction' => 'from',
				'p2p_key'       => $this->post_type . '_to_contacts',
				'tile'          => 'other',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-type.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-group.svg',
				'show_in_table' => 35
			];
		}

		/**
		 * This adds connection to groups. remove if not needed.
		 */
		if ( $post_type === 'groups' ) {
			$fields[ $this->post_type ] = [
				'name'          => $this->plural_name,
				'description'   => '',
				'type'          => 'connection',
				'post_type'     => $this->post_type,
				'p2p_direction' => 'from',
				'p2p_key'       => $this->post_type . '_to_groups',
				'tile'          => 'other',
				'icon'          => get_template_directory_uri() . '/dt-assets/images/group-type.svg',
				'create-icon'   => get_template_directory_uri() . '/dt-assets/images/add-group.svg',
				'show_in_table' => 35
			];
		}

		return $fields;
	}

	//filter at the start of post update

	/**
	 * Define tiles
	 * @link https://github.com/DiscipleTools/Documentation/blob/master/Theme-Core/field-and-tiles.md
	 */
	public function dt_details_additional_tiles( $tiles, $post_type = '' ) {
		if ( $post_type === $this->post_type ) {
			$tiles['connections'] = [ 'label' => __( 'Connections', 'cz-plugin' ) ];
			$tiles['other']       = [ 'label' => __( 'Other', 'cz-plugin' ) ];
		}

		return $tiles;
	}


	//filter when a comment is created

	/**
	 * Define additional section content
	 * Documentation
	 * @link https://github.com/DiscipleTools/Documentation/blob/master/Theme-Core/field-and-tiles.md#add-custom-content
	 */
	public function dt_details_additional_section( $section, $post_type ) {

		if ( $post_type === $this->post_type && $section === 'other' ) {
			$fields = DT_Posts::get_post_field_settings( $post_type );
			$post   = DT_Posts::get_post( $this->post_type, get_the_ID() );
			?>
            <div class="section-subheader">
				<?php esc_html_e( 'Custom Section Contact', 'cz-plugin' ) ?>
            </div>
            <div>
                <p>Add information or custom fields here</p>
            </div>

		<?php }
	}

	// filter at the start of post creation

	/**
	 * action when a post connection is added during create or update
	 *
	 * The next three functions are added, removed, and updated of the same field concept
	 */
	public function post_connection_added( $post_type, $post_id, $field_key, $value ) {
//        if ( $post_type === $this->post_type ){
//            if ( $field_key === "members" ){
//                // execute your code here, if field key match
//            }
//            if ( $field_key === "coaches" ){
//                // execute your code here, if field key match
//            }
//        }
//        if ( $post_type === "contacts" && $field_key === $this->post_type ){
//            // execute your code here, if a change is made in contacts and a field key is matched
//        }
	}

	/**
	 * action when a post has been created
	 *
	 * @param $post_type
	 * @param $post_id
	 * @param $field_key
	 * @param $value
	 *
	 * @return void
	 */
	public function post_connection_removed( $post_type, $post_id, $field_key, $value ) {
//        if ( $post_type === $this->post_type ){
//            // execute your code here, if connection removed
//        }
	}

	/**
	 * list page filters function
	 *
	 * @param $fields
	 * @param $post_type
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function dt_post_update_fields( $fields, $post_type, $post_id ) {
//        if ( $post_type === $this->post_type ){
//            // execute your code here
//        }
		return $fields;
	}

	/**
	 * list page filters function
	 *
	 * @param $post_type
	 * @param $post_id
	 * @param $comment_id
	 * @param $type
	 *
	 * @return void
	 */
	public function dt_comment_created( $post_type, $post_id, $comment_id, $type ) {
	}

	/**
	 * build list page filters
	 *
	 * @param $fields
	 * @param $post_type
	 *
	 * @return mixed
	 */

	public function dt_post_create_fields( $fields, $post_type ) {
		if ( $post_type === $this->post_type ) {
			$post_fields = DT_Posts::get_post_field_settings( $post_type );
			if ( isset( $post_fields['status'] ) && ! isset( $fields['status'] ) ) {
				$fields['status'] = 'active';
			}
		}

		return $fields;
	}

	/**
	 * access permission
	 *
	 * @param $post_type
	 * @param $post_id
	 * @param $initial_fields
	 *
	 * @return void
	 */

	public function dt_post_created( $post_type, $post_id, $initial_fields ) {
	}

	/**
	 * Enqueue scripts
	 * @return void
	 */
	public function scripts() {
		if ( is_singular( $this->post_type ) && get_the_ID() && DT_Posts::can_view( $this->post_type, get_the_ID() ) ) {
			$test = '';
			// @todo add enqueue scripts
		}
	}
}
