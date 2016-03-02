# October CMS Weather plugin

## About

This is a weather plugin for [October CMS](https://octobercms.com/) data is loaded from [Weather Underground API](https://www.wunderground.com/weather/api/).

This plugin is inspired by [Building the weather plugin](http://octobercms.com/blog/post/building-the-weather-plugin) created by Samuel Georges (daftspunk).

## Prerequisite

* Install October CMS
* Configure or install a October CMS Theme
* Install this plugin in the directory "[web_root]/plugins/fes/weather"
* Sign up for a Weather Underground API Key
* Read and agree to the [Weather Underground API terms and conditions of use](http://www.wunderground.com/weather/api/d/terms.html)
* Login to the /backend of your October CMS Go to Settings -> Plugins -> Weather -> Manage Weather settings.
* Fill in the API Base URL: [http://ap.wunderground.com/api/]
* Fill in the API Key: You should have received on while you signed up for the Weather Underground API

### Plugin properties

The plugin defines the Weather component that have the following properties:

* noRecordsMessage (required): [No records found]- value displayed when there are no weather records found
* features (required): [forecast] - one or more features. Note that these can be combined into a single request: geolookup/conditions/forecast
* settings (optional): [] - One or more of the settings, given as key:value pairs separated by a colon, for example [lang:NL]
* query (required): [Netherlands/Amsterdam] - The location for which you want weather information.
* format (required): [json] - The output format of the API call
* cache_time (required) : [1800] - Number of seconds to cache the result. There is a daily limit and minute rate limit to the number of requests that can be made to the API. While developing put in a low Cachetime on production sites set it higher for example 1800 to have a 30 minutes cache
* cache_key (required): [1] - A unique key for the caching system. Apply a different value when using this componenent multiple times or with different settings.

**For details consult the Weather Underground API documentation**

### How to use this component in October CMS

To use the component, drop it on a page, fill in the plugin properties and use the `{% component 'weather' %}` tag anywhere in the page code to render it. The next example shows a simplest page code that uses the weather component:

```
title = "Weather"
url = "/weather"

[weather]
noRecordsMessage = "No records found"
features = "forecast"
settings = "lang:NL"
query = "Netherlands/Amsterdam"
format = "json"
cache_time = 1800
cache_key = 1
==
<!DOCTYPE html>
  <html>
    <head>
      <title>Weather component example</title>
    </head>
    <body>
      {% component 'weather' %}
    </body>
</html>
```

This plugin comes with a minimal default markup file that you can find in "[web_root]/plugins/fes/weather/components/weather/default.htm". In "[web_root]/plugins/fes/weather/components/weather/debug.htm" you can find a version that is long-winded.
