<?php namespace Fes\Weather\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'fes_weather_settings';
    public $settingsFields = 'fields.yaml';
}
