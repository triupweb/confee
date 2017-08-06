<?php


namespace Confee\Units\Authentication\Http\Routes;


use Confee\Support\Http\Routing\RouteFile;
use Illuminate\Http\Request;

class Api extends RouteFile
{

    /**
     * @return mixed
     */
    protected function routes()
    {
        $this->v1Routes();
        $this->authenticationRoutes();
    }

    protected function defaultRoutes()
    {
        $this->userRoutes();
    }

    protected function v1Routes()
    {
        $this->router->group(['prefix' => 'v1'], function (){
            $this->defaultRoutes();
        });
    }

    protected function userRoutes()
    {
        $this->router->get('/user', function (Request $request) {
            return $request->user();
        })->middleware('auth:api');
    }

    protected function authenticationRoutes()
    {
       // $this->router->post('login', 'LoginController@login');
    }
}