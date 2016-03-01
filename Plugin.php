<?php namespace Fes\Weather;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
      return [
        '\Fes\Weather\Components\Weather' => 'weather'
      ];
    }

    public function registerSettings()
    {
      return [
          'settings' => [
            'label'       => 'fes.weather::lang.plugin.name',
            'description' => 'fes.weather::lang.plugin.description',
            'category'    => 'Plugins',
            'icon'        => 'icon-sun-o',
            'class'       => 'Fes\Weather\Models\Settings',
            'order'       => 100
        ]
      ];
    }

    public function registerMarkupTags()
    {

      return [
        'filters' => [
          'cast_to_array' => [$this, 'castToArray']
          ]
      ];

    }

    public function castToArray($stdClassObject) {
      $response = array();
      foreach ($stdClassObject as $key => $value) {
        $response[] = array($key, $value);
      }
      return $response;
    }

}