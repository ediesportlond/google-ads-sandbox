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

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';

use Connection\AuthAndConnect;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V12\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V12\ResourceNames;
use Google\Ads\GoogleAds\V12\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V12\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V12\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V12\Services\KeywordAndUrlSeed;
use Google\Ads\GoogleAds\V12\Services\KeywordSeed;
use Google\Ads\GoogleAds\V12\Services\UrlSeed;
use Google\ApiCore\ApiException;

/** This example generates keyword ideas from a list of seed keywords or a seed page URL. */
class GenerateKeywordIdeas
{
    private const CUSTOMER_ID = CUSTOMER_ID;
    // Location criteria IDs. For example, specify 21167 for New York. For more information
    // on determining this value, see
    // https://developers.google.com/adwords/api/docs/appendix/geotargeting.
    private const LOCATION_ID_1 = '2840';
    private const LOCATION_ID_2 = '2850';

    // A language criterion ID. For example, specify 1000 for English. For more information
    // on determining this value, see
    // https://developers.google.com/adwords/api/docs/appendix/codes-formats#languages.
    private const LANGUAGE_ID = 1000;

    private const KEYWORD_TEXT_1 = '';
    private const KEYWORD_TEXT_2 = '';

    // Optional: Specify a URL string related to your business to generate ideas.
    private const PAGE_URL = null;

    public static function main(String $keyword, String $keyword2)
    {

        $googleAdsClient = AuthAndConnect::getClient();

        try {
            self::runExample(
                $googleAdsClient,
                self::CUSTOMER_ID,
                [self::LOCATION_ID_1, self::LOCATION_ID_2],
                self::LANGUAGE_ID,
                // [self::KEYWORD_TEXT_1, self::KEYWORD_TEXT_2],
                [$keyword, $keyword2],
                self::PAGE_URL
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
     * @param int[] $locationIds the location IDs
     * @param int $languageId the language ID
     * @param string[] $keywords the list of keywords to use as a seed for ideas
     * @param string|null $pageUrl optional URL related to your business to use as a seed for ideas
     */
    public static function runExample(
        GoogleAdsClient $googleAdsClient,
        int $customerId,
        array $locationIds,
        int $languageId,
        array $keywords,
        ?string $pageUrl
    ) {
        $keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();

        // Make sure that keywords and/or page URL were specified. The request must have exactly one
        // of urlSeed, keywordSeed, or keywordAndUrlSeed set.
        if (empty($keywords) && is_null($pageUrl)) {
            throw new \InvalidArgumentException(
                'At least one of keywords or page URL is required, but neither was specified.'
            );
        }

        // Specify the optional arguments of the request as a keywordSeed, urlSeed,
        // or keywordAndUrlSeed.
        $requestOptionalArgs = [];
        if (empty($keywords)) {
            // Only page URL was specified, so use a UrlSeed.
            $requestOptionalArgs['urlSeed'] = new UrlSeed(['url' => $pageUrl]);
        } elseif (is_null($pageUrl)) {
            // Only keywords were specified, so use a KeywordSeed.
            $requestOptionalArgs['keywordSeed'] = new KeywordSeed(['keywords' => $keywords]);
        } else {
            // Both page URL and keywords were specified, so use a KeywordAndUrlSeed.
            $requestOptionalArgs['keywordAndUrlSeed'] =
                new KeywordAndUrlSeed(['url' => $pageUrl, 'keywords' => $keywords]);
        }

        // Create a list of geo target constants based on the resource name of specified location
        // IDs.
        $geoTargetConstants =  array_map(function ($locationId) {
            return ResourceNames::forGeoTargetConstant($locationId);
        }, $locationIds);

        // Generate keyword ideas based on the specified parameters.
        $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas(
            [
                // Set the language resource using the provided language ID.
                'language' => ResourceNames::forLanguageConstant($languageId),
                'customerId' => $customerId,
                // Add the resource name of each location ID to the request.
                'geoTargetConstants' => $geoTargetConstants,
                // Set the network. To restrict to only Google Search, change the parameter below to
                // KeywordPlanNetwork::GOOGLE_SEARCH.
                'keywordPlanNetwork' => KeywordPlanNetwork::GOOGLE_SEARCH_AND_PARTNERS
            ] + $requestOptionalArgs
        );

        // Iterate over the results and print its detail.
        foreach ($response->iterateAllElements() as $result) {
            /** @var GenerateKeywordIdeaResult $result */
            // Note that the competition printed below is enum value.
            // For example, a value of 2 will be returned when the competition is 'LOW'.
            // A mapping of enum names to values can be found at KeywordPlanCompetitionLevel.php.
            printf(
                "<p>Keyword idea text <strong>'%s'</strong> has <strong>%d</strong> average monthly searches and competition as %d.%s</p>",
                $result->getText(),
                is_null($result->getKeywordIdeaMetrics()) ?
                    0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches(),
                is_null($result->getKeywordIdeaMetrics()) ?
                    0 : $result->getKeywordIdeaMetrics()->getCompetition(),
                PHP_EOL
            );
        }
    }
}
      