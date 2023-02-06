<?php

use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../secrets.php";
require_once __DIR__ . "/autoload.php";

class TEST
{
  public static function main()
  {
  
    /** @var GoogleAdsClient $googleAdsClient **/
   $googleAdsServiceClient = AuthAndConnect::connect();

        // Creates a query that retrieves the specified customer.
        $query = 'SELECT customer.id, '
            . 'customer.descriptive_name, '
            . 'customer.currency_code, '
            . 'customer.time_zone, '
            . 'customer.tracking_url_template, '
            . 'customer.auto_tagging_enabled '
            . 'FROM customer '
            // Limits to 1 to clarify that selecting from the customer resource will always return
            // only one row, which will be for the customer ID specified in the request.
            . 'LIMIT 1';
        /** @var Customer $customer */
        $customer = $googleAdsServiceClient->search(LINKED_ID, $query)
            ->getIterator()
            ->current()
            ->getCustomer();

        // Print information about the account.
        printf(
            "Customer with ID %d, descriptive name '%s', currency code '%s', timezone '%s', "
            . "tracking URL template '%s' and auto tagging enabled '%s' was retrieved.%s",
            $customer->getId(),
            $customer->getDescriptiveName(),
            $customer->getCurrencyCode(),
            $customer->getTimeZone(),
            is_null($customer->getTrackingUrlTemplate())
                ? 'N/A' : $customer->getTrackingUrlTemplate(),
            $customer->getAutoTaggingEnabled() ? 'true' : 'false',
            PHP_EOL
        );
  }
}

Test::main();
