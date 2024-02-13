<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\template;

class UserController
{

    /**
     * You can also return a string or array from a controller method,
     * it will be automatically added to the response object.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     */
    public function current(Request $request, Response $response)
    {

        return template('user', [
            'user' => wp_get_current_user()
        ]);
    }

    /**
     * Fetches and displays the details of a user.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     * @param int $id Mapped from the ID route parameter.
     */
    public function show(Request $request, Response $response, $id)
    {
        $user = get_user_by('id', $id);

        return template('user', [
            'user' => $user
        ]);
    }

}
