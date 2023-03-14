<?php

use Connection\AuthAndConnect;

class GetCustomerInfo
{
  public static function getInfo()
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
        $resArray = [
            "customerId" => $customer->getId(),
            "descriptiveName" => $customer->getDescriptiveName(),
            "currencyCode" => $customer->getCurrencyCode(),
            "timezone" => $customer->getTimeZone(),
            "treackingUrlTemplate" => is_null($customer->getTrackingUrlTemplate())
                ? 'N/A' : $customer->getTrackingUrlTemplate(),
            "autoTagging" => $customer->getAutoTaggingEnabled() ? 'true' : 'false',
        ];
        
        // header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($resArray);

        return;
  }
}
