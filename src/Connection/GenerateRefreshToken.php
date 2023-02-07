<?php

use Google\Auth\OAuth2;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Google\Auth\CredentialsLoader;
use React\EventLoop\Loop;
use React\Http\Message\Response;

//only needed for testing.
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../secrets.php";

class GenerateRefreshToken
{

  private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';
  private const OAUTH2_CALLBACK_IP_ADDRESS = '127.0.0.1';
  private const SCOPE = 'https://www.googleapis.com/auth/adwords';

  public static function getNewToken()
  {

    $ini_array = parse_ini_file(CONFIG_PATH);

    $socket = new SocketServer(self::OAUTH2_CALLBACK_IP_ADDRESS . ':0');
    $redirectUrl = str_replace('tcp:', 'http:', $socket->getAddress());

    $oauth2 = new OAuth2([
      'clientId' => $ini_array['clientId'],
      'clientSecret' => $ini_array['clientSecret'],
      'authorizationUri' => self::AUTHORIZATION_URI,
      'redirectUri' => $redirectUrl,
      'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
      'scope' => self::SCOPE,
      // Create a 'state' token to prevent request forgery. See
      // https://developers.google.com/identity/protocols/OpenIDConnect#createxsrftoken
      // for details.
      'state' => sha1(openssl_random_pseudo_bytes(1024))
    ]);

    $authToken = null;

    $server = new HttpServer(function (ServerRequestInterface $request) use ($oauth2, &$authToken) {

      // Stops the server after tokens are retrieved.
      if (!is_null($authToken)) {
        Loop::stop();
      }

      // Exit if the state is invalid to prevent request forgery.
      $state = $request->getQueryParams()['state'];
      if (empty($state) || ($state !== $oauth2->getState())) {
        throw new UnexpectedValueException(
          "The state is empty or doesn't match expected one." . PHP_EOL
        );
      };

      // Set the authorization code and fetch refresh and access tokens.
      $code = $request->getQueryParams()['code'];
      $oauth2->setCode($code);
      $authToken = $oauth2->fetchAuthToken();

      $refreshToken = $authToken['refresh_token'];
      print 'Your refresh token is: ' . $refreshToken . PHP_EOL;

      return new Response(
        200,
        ['Content-Type' => 'text/plain'],
        'Your refresh token has been fetched. Check the console output for '
        . 'further instructions.'
    );

    });

    $server->listen($socket);
    printf(
        'Log into the Google account you use for Google Ads and visit the following URL '
        . 'in your web browser: %1$s%2$s%1$s%1$s',
        PHP_EOL,
        $oauth2->buildFullAuthorizationUri(['access_type' => 'offline'])
    );

  }
}

GenerateRefreshToken::getNewToken();
