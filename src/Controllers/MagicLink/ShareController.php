<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\CodeZone\Router\Factories\RedirectResponseFactory;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\container;
use function DT\Home\magic_url;
use function DT\Home\redirect;
use function DT\Home\route_url;

class ShareController
{
    public function show( Request $request, Response $response, $key )
    {
        $user_id = get_current_user_id();
        $contact = \Disciple_Tools_Users::get_contact_for_user( $user_id );

        if ( $contact !== null ) {
            setcookie( 'dt_home_share', $contact, time() + ( 86400 * 30 ), "/" );
        }

        return redirect( route_url() );
    }
}
