<?php

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__. "/../secrets.php";

class TEST
{
  public static function main()
  {

    $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(CONFIG_PATH)->build();

    $googleAdsClient = (new GoogleAdsClientBuilder())
      ->fromFile(CONFIG_PATH)
      ->withOAuth2Credential($oAuth2Credential)
      ->build();

    // Make a request to the Google Ads API.
    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

    print_r($googleAdsServiceClient);

  }
}

Test::main();
