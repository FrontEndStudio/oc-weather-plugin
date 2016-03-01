<?php namespace Fes\Weather\Components;

use Fes\Weather\Models\Settings;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Lang;
use Exception;
use SystemException;
use Cache;
use Carbon\Carbon;
use Request;

class Weather extends ComponentBase
{

    public $records;
    public $noRecordsMessage;
    public $errorMessage;
    public $api_base_url;
    public $api_key;
    public $features;
    public $settings;
    public $query;
    public $format;
    public $cache_time;
    public $cache_key;

    public function componentDetails()
    {
        return [
            'name'        => 'fes.weather::lang.plugin.name',
            'description' => 'fes.weather::lang.plugin.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'noRecordsMessage' => [
                'title'        => 'fes.weather::lang.components.list_no_records',
                'description'  => 'fes.weather::lang.components.list_no_records_description',
                'type'         => 'string',
                'default'      => Lang::get('fes.weather::lang.components.list_no_records_default'),
                'showExternalParam' => false,
            ],
            'features' => [
                'title'             => 'fes.weather::lang.properties.features_title',
                'description'       => 'fes.weather::lang.properties.features_description',
                'type'              => 'string',
                'default'           => 'forecast',
                'required'          => true
            ],
            'settings' => [
                'title'             => 'fes.weather::lang.properties.settings_title',
                'description'       => 'fes.weather::lang.properties.settings_description',
                'type'              => 'string',
                'required'          => false
            ],
            'query' => [
                'title'             => 'fes.weather::lang.properties.query_title',
                'description'       => 'fes.weather::lang.properties.query_description',
                'type'              => 'string',
                'default'           => 'Netherlands/Amsterdam',
                'required'          => true
            ],
            'format' => [
                'title'             => 'fes.weather::lang.properties.format_title',
                'type'              => 'dropdown',
                'default'           => 'json',
                'options'           => ['json' => 'JSON'],
                'required'          => true
            ],
            'cache_time' => [
                'title'             => 'fes.weather::lang.properties.cache_time_title',
                'description'       => 'fes.weather::lang.properties.cache_time_description',
                'default'           => 1800,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'fes.weather::lang.properties.cache_time_validation',
                'required'          => true
            ],
            'cache_key' => [
                'title'             => 'fes.weather::lang.properties.cache_key_title',
                'description'       => 'fes.weather::lang.properties.cache_key_description',
                'default'           => '1',
                'type'              => 'string',
                'validationPattern' => '^[0-9a-zA-Z_]+$',
                'validationMessage' => 'fes.weather::lang.properties.cache_key_validation',
                'required'          => true
            ]
        ];
    }


    public function onRun()
    {
        $this->prepareVars();

        if ( ! isset($this->errorMessage) ) {
            $this->records = $this->page['records'] = $this->listRecords();
        }

    }

    protected function prepareVars()
    {

        $this->noRecordsMessage = $this->page['noRecordsMessage'] = Lang::get($this->property('noRecordsMessage'));

        $this->api_base_url = Settings::get('api_base_url');

        $pattern = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS';
        if ( ! preg_match($pattern, $this->api_base_url) ) {
            return $this->errorMessage = Lang::get('fes.weather::lang.settings.api_base_url_validation');
        }

        $this->api_key = Settings::get('api_key');

        if ($this->api_key == '') {
            return $this->errorMessage = Lang::get('fes.weather::lang.settings.api_key_validation');
        }

        $this->features = $this->property('features', 'forecast');
        $this->settings = $this->property('settings', '');
        $this->query = $this->property('query', 'Netherlands/Amsterdam');
        $this->format = $this->property('format', 'json');
        $this->cache_time = $this->property('cache_time', '1800');
        $this->cache_key = $this->property('cache_key', '1');

    }

    protected function listRecords()
    {

        // $url = 'http://api.wunderground.com/api/123/forecast/lang:NL/q/Netherlands/Amsterdam.xml';
        // $url = '{{ api_base_url }}{{ api_key }}/{{ features}}/{{ settings : ''}}/q/{{ query }}.{{ format}}';

        if ($this->settings != '') {

            $url = sprintf("%s%s/%s/%s/q/%s.%s",
                $this->api_base_url,
                $this->api_key,
                $this->features,
                $this->settings,
                $this->query,
                $this->format
            );

        } else {

            $url = sprintf("%s/api/%s/%s/q/%s.%s",
                $this->api_base_url,
                $this->api_key,
                $this->features,
                $this->query,
                $this->format
            );

        }

        $format = $this->format;
        $expiresAt = Carbon::now()->addSeconds($this->cache_time);

        $weatherRecords = Cache::remember('weatherRecords_' + $this->cache_key, $expiresAt, function() use ($url, $format, $expiresAt) {

            $expiresTime = $expiresAt->hour . ':' . $expiresAt->minute . ':' . $expiresAt->second;

            // xml not implemented
            if ($format == 'xml') {
                return;
            } else {
                ini_set("allow_url_fopen", "1");
                $records = file_get_contents($url);
                ini_set("allow_url_fopen", "0");
                $records = json_decode($records);
                $records->response->expirestime = $expiresTime;
                $records->response->url = $url;
                return $records;
            }

        });

        if(!empty($weatherRecords))
        {
            return $weatherRecords;
        }
        else
        {
            return $this->errorMessage = Lang::get('fes.weather::lang.settings.error');
        }


    }

}
