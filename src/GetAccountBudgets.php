<?php

/**
 * Copyright 2018 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// namespace Google\Ads\GoogleAds\Examples\Billing;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';

use Connection\AuthAndConnect;
use Google\Ads\GoogleAds\Examples\Utils\Helper;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V12\Enums\AccountBudgetStatusEnum\AccountBudgetStatus;
use Google\Ads\GoogleAds\V12\Enums\SpendingLimitTypeEnum\SpendingLimitType;
use Google\Ads\GoogleAds\V12\Enums\TimeTypeEnum\TimeType;
use Google\Ads\GoogleAds\V12\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;

/** This example retrieves all account budgets for a Google Ads customer. */
class GetAccountBudgets
{

  public static function getBudgets()
  {

    /** @var GoogleAdsClient $googleAdsClient **/
    $googleAdsClient = AuthAndConnect::getClient();

    try {
      return self::runExample(
        $googleAdsClient,
        CUSTOMER_ID
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
  public static function runExample(GoogleAdsClient $googleAdsClient, int $customerId)
  {
    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
    // Creates a query that retrieves the account budgets.
    $query = 'SELECT account_budget.status, '
      . 'account_budget.billing_setup, '
      . 'account_budget.approved_spending_limit_micros, '
      . 'account_budget.approved_spending_limit_type, '
      . 'account_budget.proposed_spending_limit_micros, '
      . 'account_budget.proposed_spending_limit_type, '
      . 'account_budget.adjusted_spending_limit_micros, '
      . 'account_budget.adjusted_spending_limit_type, '
      . 'account_budget.approved_start_date_time, '
      . 'account_budget.proposed_start_date_time, '
      . 'account_budget.approved_end_date_time, '
      . 'account_budget.approved_end_time_type,'
      . 'account_budget.proposed_end_date_time, '
      . 'account_budget.proposed_end_time_type '
      . 'FROM account_budget';

    // Issues a search request by specifying page size.
    $stream = $googleAdsServiceClient->searchStream($customerId, $query);
    /** @var GoogleAdsServerStreamDecorator $stream */

    $data = [];
    // Iterates over all rows in all pages and prints the requested field values for
    // the account budget in each row.
    foreach ($stream->iterateAllElements() as $googleAdsRow) {
      /** @var GoogleAdsRow $googleAdsRow */
      $accountBudget = $googleAdsRow->getAccountBudget();

      $resourceName = $accountBudget->getResourceName();

      $data[$resourceName] = [
        "status" => AccountBudgetStatus::name($accountBudget->getStatus()),
        "billing_setup" => $accountBudget->getBillingSetup()
          ? $accountBudget->getBillingSetup() : 'none',
        "amount_served_micros" => $accountBudget->getAmountServedMicros(),
        "total_adjustments_micros" => $accountBudget->getTotalAdjustmentsMicros(),
        "approved_spending_limit_micros" => $accountBudget->getApprovedSpendingLimitMicros()
          ? $accountBudget->getApprovedSpendingLimitMicros()
          : SpendingLimitType::name($accountBudget->getApprovedSpendingLimitType()),
        "proposed_spending_limit_micros" => $accountBudget->getProposedSpendingLimitMicros()
          ? $accountBudget->getProposedSpendingLimitMicros()
          : SpendingLimitType::name($accountBudget->getProposedSpendingLimitType()),
        "adjusted_spending_limit_micros" => $accountBudget->getAdjustedSpendingLimitMicros()
          ? $accountBudget->getAdjustedSpendingLimitMicros()
          : SpendingLimitType::name($accountBudget->getAdjustedSpendingLimitType()),
        "approved_start_datetime" => $accountBudget->getApprovedStartDateTime()
          ? $accountBudget->getApprovedStartDateTime()
          : 'none',
        "prposed_start_datetime" => $accountBudget->getProposedStartDateTime()
          ? $accountBudget->getProposedStartDateTime()
          : 'none',
        "approved_end_datetime" => $accountBudget->getApprovedEndDateTime()
          ? $accountBudget->getApprovedEndDateTime()
          : TimeType::name($accountBudget->getApprovedEndTimeType()),
        "proposed_end_datetime" => $accountBudget->getProposedEndDateTime()
          ? $accountBudget->getProposedEndDateTime()
          : TimeType::name($accountBudget->getProposedEndTimeType()),
      ];

      // printf(
      //   'Found the account budget \'%s\' with status \'%s\', billing setup '
      //     . '\'%s\', amount served %.2f, total adjustments %.2f,%s'
      //     . '  approved spending limit \'%s\' (proposed \'%s\', adjusted \'%s\'),%s'
      //     . '  approved start time \'%s\' (proposed \'%s\'),%s'
      //     . '  approved end time \'%s\' (proposed \'%s\').%s',
      //   $accountBudget->getResourceName(),
      //   AccountBudgetStatus::name($accountBudget->getStatus()),
      //   $accountBudget->getBillingSetup()
      //     ? $accountBudget->getBillingSetup() : 'none',
      //   Helper::microToBase($accountBudget->getAmountServedMicros()),
      //   Helper::microToBase($accountBudget->getTotalAdjustmentsMicros()),
      //   PHP_EOL,
      //   $accountBudget->getApprovedSpendingLimitMicros()
      //     ? sprintf(
      //       '%.2f',
      //       Helper::microToBase($accountBudget->getApprovedSpendingLimitMicros())
      //     ) : SpendingLimitType::name($accountBudget->getApprovedSpendingLimitType()),
      //   $accountBudget->getProposedSpendingLimitMicros()
      //     ? sprintf(
      //       '%.2f',
      //       Helper::microToBase($accountBudget->getProposedSpendingLimitMicros())
      //     ) : SpendingLimitType::name($accountBudget->getProposedSpendingLimitType()),
      //   $accountBudget->getAdjustedSpendingLimitMicros()
      //     ? sprintf(
      //       '%.2f',
      //       Helper::microToBase($accountBudget->getAdjustedSpendingLimitMicros())
      //     ) : SpendingLimitType::name($accountBudget->getAdjustedSpendingLimitType()),
      //   PHP_EOL,
      //   $accountBudget->getApprovedStartDateTime()
      //     ? $accountBudget->getApprovedStartDateTime()
      //     : 'none',
      //   $accountBudget->getProposedStartDateTime()
      //     ? $accountBudget->getProposedStartDateTime()
      //     : 'none',
      //   PHP_EOL,
      //   $accountBudget->getApprovedEndDateTime()
      //     ? $accountBudget->getApprovedEndDateTime()
      //     : TimeType::name($accountBudget->getApprovedEndTimeType()),
      //   $accountBudget->getProposedEndDateTime()
      //     ? $accountBudget->getProposedEndDateTime()
      //     : TimeType::name($accountBudget->getProposedEndTimeType()),
      //   PHP_EOL
      // );
    }

    return $data;
  }
}
