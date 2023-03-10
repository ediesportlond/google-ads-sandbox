<?php

namespace Connection;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClientBuilder;

class AuthAndConnect
{
  /**
   * @return GoogleAdsServiceClient
   */
  public static function connect()
  {
    $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(CONFIG_PATH)->build();

    $googleAdsClient = (new GoogleAdsClientBuilder())
      ->fromFile(CONFIG_PATH)
      ->withOAuth2Credential($oAuth2Credential)
      ->build();

    // Make a request to the Google Ads API.
    return $googleAdsClient->getGoogleAdsServiceClient();
  }

  /**
   * @return GoogleAdsClient
   */
  public static function getClient()
  {
    $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(CONFIG_PATH)->build();

    $googleAdsClient = (new GoogleAdsClientBuilder())
      ->fromFile(CONFIG_PATH)
      ->withOAuth2Credential($oAuth2Credential)
      ->build();

    // Make a request to the Google Ads API.
    return $googleAdsClient;
  }
}
