<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Google;

class OAuth2Controller extends Controller
{
    public function consent(Request $request)
    {
        $provider = $this->provider();

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['https://www.googleapis.com/auth/gmail.send'],
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        $request->session()->put('oauth2_state', $provider->getState());

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $state = $request->query('state');
        $code = $request->query('code');

        if (!$code || !$state || $state !== $request->session()->pull('oauth2_state')) {
            return response()->json(['message' => 'Invalid OAuth state.'], 400);
        }

        $token = $this->provider()->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return response()->json([
            'refresh_token' => $token->getRefreshToken(),
            'access_token' => $token->getToken(),
            'expires' => $token->getExpires(),
        ]);
    }

    private function provider(): Google
    {
        return new Google([
            'clientId' => config('services.gmail.client_id'),
            'clientSecret' => config('services.gmail.client_secret'),
            'redirectUri' => config('services.gmail.redirect_uri'),
        ]);
    }
}
