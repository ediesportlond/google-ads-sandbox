<?php

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use GPBMetadata\Google\Ads\GoogleAds\V10\Enums\CriterionType;
use GPBMetadata\Google\Ads\GoogleAds\V10\Enums\KeywordMatchType;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../secrets.php";

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

    // print_r($googleAdsServiceClient);

    $query =
      'SELECT ad_group.id, '
      . 'ad_group_criterion.type, '
      . 'ad_group_criterion.criterion_id, '
      . 'ad_group_criterion.keyword.text, '
      . 'ad_group_criterion.keyword.match_type '
      . 'FROM ad_group_criterion '
      . 'WHERE ad_group_criterion.type = KEYWORD';

    $response =
      $googleAdsServiceClient->search(CUSTOMER_ID, $query, ['pageSize' => 10]);

    foreach ($response->iterateAllElements() as $googleAdsRow) {

      $resourceNameString = printf(
        " and resource name '%s'",
        $googleAdsRow->getAdGroup()->getResourceName()
      );

      printf(
        "Keyword with text '%s', match type '%s', criterion type '%s', and ID %d "
        . "was found in ad group with ID %d%s.%s",
        $googleAdsRow->getAdGroupCriterion()->getKeyword()->getText(),
        KeywordMatchType::name( 
            $googleAdsRow->getAdGroupCriterion()->getKeyword()->getMatchType()
        ),
        CriterionType::name($googleAdsRow->getAdGroupCriterion()->getType()),
        $googleAdsRow->getAdGroupCriterion()->getCriterionId(),
        $googleAdsRow->getAdGroup()->getId(),
        $resourceNameString,
        PHP_EOL
    );

    }

  }
}

Test::main();
