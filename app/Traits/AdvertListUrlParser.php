<?php

namespace App\Traits;

use App\Administrative;
use App\City;
use App\Street;
use App\Subway;

trait AdvertListUrlParser
{
    protected $path;
    protected $query;
    protected $request;

    public $city;
    public $geoObject;
    public $geoFilter = [];
    public $advertFilter = [];
    public $paramFilter = [];
    public $optionFilter = [];
    public $order;
    public $query_body;
    public $page;

    public function processUrl($url)
    {
        $this->parseUrl($url);

        while(!empty($this->path[0])) {
            $this->getStrictParams();
            $this->getGeoObjects();

            $this->pathShift();
        }

        $this->getParams();
        $this->getPage();
    }

    protected function parseUrl($url)
    {
        $this->path = explode('/', parse_url($url, PHP_URL_PATH));

        $this->pathShift();

        if(reset($this->path) === 'ru') {
            $this->setLocale('ru');
            $this->pathShift();
        }

        $this->path = explode('-', reset($this->path));
        $this->getCity();

        if(!empty(parse_url($url, PHP_URL_QUERY))){
            $this->query = explode('&', parse_url($url, PHP_URL_QUERY));
        }
    }

    protected function pathShift() {
        return array_shift($this->path);
    }

    protected function setLocale($locale)
    {
        app()->setLocale($locale);
    }

    public function getCity()
    {
        $cityTranslit = reset($this->path);

        $this->city = City::where('translit', $cityTranslit)->first();

        if (!empty($this->city->id)) {
            $this->pathShift();
        }
    }

    public function getStrictParams()
    {
        $strictTranslit = reset($this->path);

        foreach(explode(',', $strictTranslit) as $strictParamValue) {
            switch ($strictParamValue) {
                case trans('parameter_values.type.url.flat'):
                    $this->advertFilter['type'][] = 'flat';
                    break;
                case trans('parameter_values.type.url.house'):
                    $this->advertFilter['type'][] = 'house';
                    break;
                case trans('parameter_values.type.url.half-house'):
                    $this->advertFilter['type'][] = 'half-house';
                    break;
                case trans('parameter_values.type.url.room'):
                    $this->advertFilter['type'][] = 'room';
                    break;
                case trans('parameter_values.room_count.url.1'):
                   $this->advertFilter['room_count'][] = 1;
                    break;
                case trans('parameter_values.room_count.url.2'):
                    $this->advertFilter['room_count'][] = 2;
                    break;
                case trans('parameter_values.room_count.url.3'):
                    $this->advertFilter['room_count'][] = 3;
                    break;
                case trans('parameter_values.room_count.url.4'):
                    $this->advertFilter['room_count'][] = 4;
                    break;
            }
        }
    }

    public function getGeoObjects()
    {
        $geoTranslit = reset($this->path);

        $string = explode('_', $geoTranslit);
        $object = end($string);

        switch ($object) {
            case 'ulica':
                $street = Street::where('city_id', $this->city->id)
                    ->where('translit', $geoTranslit)->first();

                if(!empty($street->id)) {
                    $this->geoFilter['street'] = $street->id;
                    $this->geoObject = $street;
                }

                break;
            case 'metro':
                $subway = Subway::where('city_id', $this->city->id)
                    ->where('translit', $geoTranslit)->first();

                if(!empty($subway->id)) {
                    $this->geoFilter['subway'] = $subway->id;
                    $this->geoObject = $subway;
                }

                break;
            case 'rajon':
                $administrative = Administrative::where('city_id', $this->city->id)
                    ->where('translit', $geoTranslit)->first();

                if(!empty($administrative->id)) {
                    $this->geoFilter['administrative'] = $administrative->id;

                    $this->geoObject = $administrative;
                }

                break;
        }
    }

    protected function getParams() {
        if(!empty($this->query)) {
            foreach($this->query as $param) {
                list($key, $value) = explode('=', $param);

                switch($key) {
                    case 'pricemonth_min':
                        $this->advertFilter['price_month']['from'] = $value;
                        break;

                    case 'pricemonth_max':
                        $this->advertFilter['price_month']['to'] = $value;
                        break;

                    case 'publish_date':
                        $this->advertFilter['publish_date'] = $value;
                        break;

                    case 'total_space_min':
                        $this->paramFilter['total_space']['from'] = $value;
                        break;

                    case 'total_space_max':
                        $this->paramFilter['total_space']['to'] = $value;
                        break;

                    case 'living_space_min':
                        $this->paramFilter['living_space']['from'] = $value;
                        break;

                    case 'living_space_max':
                        $this->paramFilter['living_space']['to'] = $value;
                        break;

                    case 'kitchen_space_min':
                        $this->paramFilter['kitchen_space']['from'] = $value;
                        break;

                    case 'kitchen_space_max':
                        $this->paramFilter['kitchen_space']['to'] = $value;
                        break;

                    case 'build_year_min':
                        $this->paramFilter['build_year']['from'] = $value;
                        break;

                    case 'build_year_max':
                        $this->paramFilter['build_year']['to'] = $value;
                        break;

                    case 'height_min':
                        $this->paramFilter['height']['from'] = $value;
                        break;

                    case 'height_max':
                        $this->paramFilter['height']['to'] = $value;
                        break;

                    case 'floor_min':
                        $this->paramFilter['floor']['from'] = $value;
                        break;
                    case 'floor_max':
                        $this->paramFilter['floor']['to'] = $value;
                        break;

                    case 'total_floors_min':
                        $this->paramFilter['total_floors']['from'] = $value;
                        break;

                    case 'total_floors_max':
                        $this->paramFilter['total_floors']['to'] = $value;
                        break;

                    case 'not_first_floor':
                        $this->paramFilter['not_first_floor'] = $value;
                        break;

                    case 'not_last_floor':
                        $this->paramFilter['not_last_floor'] = $value;
                        break;

                    case 'joint_rent':
                        $this->optionFilter['joint_rent'] = $value;
                        break;

                    case 'query':
                        $this->query_body = $value;
                        break;

                    case 'order':
                        $this->order = $value;
                        break;

                    case 'page':
                        $this->page = $value;
                        break;
                }
            }
        }
    }

    protected function getPage()
    {
        return $this->page;
    }
}
