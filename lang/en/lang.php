<?php return [
    'plugin' => [
        'name' => 'Weather',
        'description' => 'Plugin to display weather data'
    ],
    'settings' => [
      'api_base_url' => 'API Base URL',
      'api_base_url_validation' => 'The API Base URL is not valid or set.',
      'api_key' => 'API Key',
      'api_key_validation' => 'The API Key is not valid or set.',
      'error' => 'Something went wrong while connecting to the API. Verify your internet connection, API settings and be sure to have php.ini allow_url_fopen enabled.'
    ],
    'properties' => [
        'features_title' => 'Features',
        'features_description' => 'One or more features. Note that these can be combined into a single request: geolookup/conditions/forecast',
        'settings_title' => 'Settings',
        'settings_description' => 'One or more of the settings, given as key:value pairs separated by a colon. Example: lang:FR/pws:0',
        'query_title' => 'Query',
        'query_description' => 'The location for which you want weather information.',
        'format_title' => 'Format',
        'cache_time_title' => 'Cachetime',
        'cache_time_description' => 'Number of seconds to cache the result. There is a daily limit and minute rate limit to the number of requests that can be made to the API. While developing put in a low Cachetime on production sites set it higher for example 1800 to have a 30 minutes cache',
        'cache_time_validation' => 'The Cachetime field is required and may only contain digits.',
        'cache_key_title' => 'Cachekey',
        'cache_key_description' => 'A unique key for the caching system. Apply a different value when using this componenent multiple times or with different settings.',
        'cache_key_validation' => 'The Cachekey field is required and may only contain [0-9a-zA-Z_].'
    ],
    'components' => [
      'list_no_records' => 'No records message',
      'list_no_records_description' => 'Message to display in the list in case if there are no records. Used in the default component\'s partial.',
      'list_no_records_default' => 'No records found'
    ]
];