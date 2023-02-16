<?php

use Connection\AuthAndConnect;

use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsException;
use Google\Ads\GoogleAds\V12\Common\ManualCpm;
use Google\Ads\GoogleAds\V12\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V12\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V12\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V12\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V12\Resources\Campaign;
use Google\Ads\GoogleAds\V12\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V12\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V12\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V12\Services\CampaignOperation;
use Google\ApiCore\ApiException;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';
require __DIR__ . '/../secrets.php';

class CreateVideoCampaign
{
  private const NUMBER_OF_CAMPAIGNS_TO_ADD = 1;

  public static function createVideoCampaign()
  {

    $googleAdsClient = AuthAndConnect::getClient();

    try {
      self::create(
        $googleAdsClient,
        LINKED_ID
      );
    } catch (GoogleAdsException $googleAdsException) {
      printf(
        "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
        $googleAdsException->getRequestId(),
        PHP_EOL,
        PHP_EOL
      );

      foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
        /** @var GoogleAdsError $error */
        printf(
          "\t%s: %s%s",
          $error->getErrorCode()->getErrorCode(),
          $error->getMessage(),
          PHP_EOL
        );
      }
      exit(1);
    } catch (ApiException $apiException) {
      printf(
        "ApiException was thrown with message '%s'.%s",
        $apiException->getMessage(),
        PHP_EOL
      );
      exit(1);
    }
  }

  /**
   * Runs the example.
   *
   * @param GoogleAdsClient $googleAdsClient the Google Ads API client
   * @param int $customerId the customer ID
   */
  public static function create(GoogleAdsClient $googleAdsClient, int $customerId)
  {
    // Creates a single shared budget to be used by the campaigns added below.
    $budgetResourceName = self::addCampaignBudget($googleAdsClient, $customerId);

    // Configures the campaign network options.
    $networkSettings = new NetworkSettings([
      'target_google_search' => true,
      'target_search_network' => true,
      // Enables Display Expansion on Search campaigns. See
      // https://support.google.com/google-ads/answer/7193800 to learn more.
      'target_content_network' => true,
      'target_partner_search_network' => false
    ]);

    $campaignOperations = [];
    for ($i = 0; $i < self::NUMBER_OF_CAMPAIGNS_TO_ADD; $i++) {
      // Creates a campaign.
      $campaign = new Campaign([
        'name' => 'Interplanetary Cruise Game',
        'advertising_channel_type' => AdvertisingChannelType::DISPLAY,
        // Recommendation: Set the campaign to PAUSED when creating it to prevent
        // the ads from immediately serving. Set to ENABLED once you've added
        // targeting and the ads are ready to serve.
        'status' => CampaignStatus::PAUSED,
        // Sets the bidding strategy and budget.
        'manual_cpm' => new ManualCpm(),
        'campaign_budget' => $budgetResourceName,
        // Adds the network settings configured above.
        'network_settings' => $networkSettings,
        // Optional: Sets the start and end dates.
        'start_date' => date('Ymd', strtotime('+1 day')),
        'end_date' => date('Ymd', strtotime('+1 month'))
      ]);

      // Creates a campaign operation.
      $campaignOperation = new CampaignOperation();
      $campaignOperation->setCreate($campaign);
      $campaignOperations[] = $campaignOperation;
    }

    // Issues a mutate request to add campaigns.
    $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();
    $response = $campaignServiceClient->mutateCampaigns($customerId, $campaignOperations);

    printf("Added %d campaigns:%s", $response->getResults()->count(), PHP_EOL);

    foreach ($response->getResults() as $addedCampaign) {
      /** @var Campaign $addedCampaign */
      print "{$addedCampaign->getResourceName()}" . PHP_EOL;
    }
  }

  /**
   * Creates a new campaign budget in the specified client account.
   *
   * @param GoogleAdsClient $googleAdsClient the Google Ads API client
   * @param int $customerId the customer ID
   * @return string the resource name of the newly created budget
   */
  private static function addCampaignBudget(GoogleAdsClient $googleAdsClient, int $customerId)
  {
    // Creates a campaign budget.
    $budget = new CampaignBudget([
      'name' => 'Interplanetary Cruise Game Budget',
      'delivery_method' => BudgetDeliveryMethod::STANDARD,
      'amount_micros' => 500000
    ]);

    // Creates a campaign budget operation.
    $campaignBudgetOperation = new CampaignBudgetOperation();
    $campaignBudgetOperation->setCreate($budget);

    // Issues a mutate request.
    $campaignBudgetServiceClient = $googleAdsClient->getCampaignBudgetServiceClient();
    $response = $campaignBudgetServiceClient->mutateCampaignBudgets(
      $customerId,
      [$campaignBudgetOperation]
    );

    /** @var CampaignBudget $addedBudget */
    $addedBudget = $response->getResults()[0];
    printf("Added budget named '%s'%s", $addedBudget->getResourceName(), PHP_EOL);

    return $addedBudget->getResourceName();
  }
}

CreateVideoCampaign::createVideoCampaign();
