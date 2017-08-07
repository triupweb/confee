<?php


namespace Confee\Units\Authentication\Http\Controllers;

use Confee\Support\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{

    use ThrottlesLogins;


    /**
     * Authorize a client to access the user's account.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Dispatch a token request to Laravel's Passport.
        $requestTo = Request::create('/oauth/token', 'POST', $request->all());
        $response = app()->handle($requestTo);

        // Parse the request's response body
        $content = json_decode($response->getContent());

        // Return UNAUTHORIZED response if login credentials is invalid.
        if ($response->getStatusCode() == Response::HTTP_UNAUTHORIZED) {

            // Increments login attempts
            $this->incrementLoginAttempts($request);

            return response()->json([
                'error' => 'invalid_credentials'
            ], $response->getStatusCode());
        }

        // Return Internal Server Error when could not create the token.
        if ($response->getStatusCode() !== Response::HTTP_OK && $response->getStatusCode() !== Response::HTTP_UNAUTHORIZED) {

            // Increments login attempts
            $this->incrementLoginAttempts($request);

            return response()->json([
                'error' => 'could_not_create_token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Return Laravel's Passport token.
        return response()->json($content, Response::HTTP_OK);

    }


    /**
     * Return a HTTP_TO_MANY_REQUESTS response after determining the user has to many login fails.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);

        return response()->json([
            $message
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Returns a parameter that is used as a "username" when attempting to login.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Returns how long in minutes the user should wait after exceeding the maximum number of login attempts.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return 5;
    }

}