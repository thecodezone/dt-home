<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Illuminate\Support\Facades\App;
use DT\Home\Services\Apps;
use DT\Home\Services\SVGIconService;
use function DT\Home\view;

class AppSettingsController
{

    /**
     * Show the general settings app tab.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function show( Request $request, Response $response, Apps $apps )
    {

        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        $data = $apps->all();
	    $data = array_map(function ( $app ) {
		    return array_merge([
			    'name' => '',
			    'type' => 'webview',
			    'icon' => '',
			    'url' => '',
			    'sort' => 0,
			    'slug' => '',
			    'is_hidden' => false,
		    ], $app);
	    }, $data);

        return view( "settings/app", compact( 'tab', 'link', 'page_title', 'data' ) );
    }

    /**
     * Show the form to create a new app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function create( Request $request, Response $response )
    {
        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        return view( "settings/create", compact( 'tab', 'link', 'page_title', 'svg_icon_urls' ) );
    }

    /**
     * Store a new app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return RedirectResponse
     */
    public function store( Request $request, Response $response, Apps $apps )
    {
        // Retrieve form data
        $name = $request->input( 'name' );
        $type = $request->input( 'type' );
        $icon = $request->input( 'icon' );
        $url = $request->input( 'url' );
        $slug = $request->input( 'slug' );
        $sort = $request->input( 'sort' );
        $is_hidden = $request->input( 'is_hidden' );

        // Prepare the data to be stored
        $app_data = [
            'name' => $name,
            'type' => $type,
            'icon' => $icon,
            'url' => $url,
            'sort' => $sort,
            'slug' => $slug,
            'is_hidden' => $is_hidden,
        ];

	    $apps->create( $app_data );

	    return new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );
    }

    /**
     * Unhide an app by ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function unhide( $id, Apps $apps )
    {
        // Retrieve the existing array of apps
        $apps->update( $id, [
			'id_hidden' => 0,
        ] );

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    /**
     * Hide an app by ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function hide( $id, Apps $apps )
    {
	    // Retrieve the existing array of apps
	    $apps->update( $id, [
		    'id_hidden' => 0,
	    ] );

	    // Redirect to the page with a success message
	    $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

	    return $response;
    }

    /**
     * Move an app up in the list by ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function up( $id, Apps $apps )
    {
        $apps->up( $id );

        // Redirect to the page with a success message
        return new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );
    }

    /**
     * Move an app down in the list by ID.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function down( $id, Apps $apps )
    {
        $apps->down( $id );

        // Redirect to the page with a success message
        return new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );
	}

    /**
     * Update an existing app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return RedirectResponse
     */
    public function update( Request $request, Response $response, Apps $apps )
    {
		$name = $request->input( 'name' );
	    $type = $request->input( 'type' );
	    $icon_url = $request->input( 'icon' );
	    $url = $request->input( 'url' );
	    $sort = $request->input( 'sort' );
	    $slug = $request->input( 'slug' );
	    $is_hidden = $request->input( 'is_hidden' );

	    // Get the ID of the item being edited
	    $edit_id = $request->input( 'edit_id' );

	    $apps->update( $edit_id, [
		    'name' => $name,
		    'type' => $type,
		    'icon' => $icon_url,
		    'url' => $url,
		    'slug' => $slug,
		    'sort' => $sort,
		    'is_hidden' => $is_hidden
	    ]);

	    // Redirect to the page with a success message
	    return new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );
    }

    /**
     * Show the form to edit an existing app by ID.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function edit( $id, Response $response, Apps $apps )
    {
        $edit_id = isset( $id ) ? intval( $id ) : 0;
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        if ( ! $edit_id ) {
			return $response->setStatusCode( 404 );
        }

        // Retrieve the existing data based on $edit_id
        $existing_data = $apps->get( $edit_id );
		if ( ! $existing_data ) {
			return $response->setStatusCode( 404 );
		}

        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        return view( "settings/edit", compact( 'existing_data', 'link', 'tab', 'page_title', 'svg_icon_urls' ) );
    }
}
