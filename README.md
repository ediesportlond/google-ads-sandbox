# API DOCUMENTATION

Get metrics and manage campaigns using PHP Google Ads Library.
<a href="https://github.com/googleads/google-ads-php">Source GitHub</a>

## Getting Started

<a href="https://www.youtube.com/watch?v=HXKpfGqPRy0">Helpful video for configuration of OAUTH in Google Cloud</a>

### Create and configure ini file

#### Download ini file from source GitHub project. 
Include developerToken, loginCustomerId, linkedCustomerId under GOOGLE_ADS

Include clientId, clientSecret, refreshToken under OAUTH2

<a href="https://github.com/googleads/google-ads-php/blob/main/examples/Authentication/google_ads_php.ini">Template .ini File</a>

Place .ini file in src folder.

#### Refresh Token

Refresh token can be generated using command `php src/Connection/GenerateRefreshToken.php` in project root directory.

### Create a secrets.php file in the project root directory and add the follow values:

`define("CONFIG_PATH", __DIR__ . '/src/google_ads_php.ini');` Path to config file<br>
`define("CUSTOMER_ID", "");` Manager Account Customer Id<br>
`define("LINKED_ID", "");`  Client Account Customer Id<br>

## Endpoints

### GET

#### getInfo

`customer` Prints basic customer info. 

`budget` Prints information on account budgets.

#### getStats

`keywords` Prints stats on keywords.

#### keywords

Prints table with keyword suggestions and average monthly searches. 

`keywords=1` Required param<br>
`keywordOne` First keyword<br>
`keywordTwo` Second keyword<br>
