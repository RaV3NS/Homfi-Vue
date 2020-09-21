<?php

use Faker\Provider\uk_UA\Address as FAddress;

class RegionCityList extends FAddress
{
    public function getRegions()
    {
        return self::$region;
    }

    public function getCities()
    {
        return self::$city;
    }

    public function getRegionByCity($city)
    {
        $relation = $this->regionCityRelation();
        return array_search($city, $relation);
    }

    public function regionCityRelation()
    {
        return [
            'Вінницька' => 'Вінницька',
            'Волинська' => 'Луцьк' ,
            'Дніпропетровська' => 'Дніпро',
            'Донецька' => 'Донецьк',
            'Житомирська' => 'Житомир',
            'Закарпатська' => 'Ужгород',
            'Запорізька' => 'Запоріжжя',
            'Івано-Франківська' => 'Івано-Франківськ',
            'Київська' => 'Київ',
            'Кіровоградська' => 'Кропивницький',
            'Луганська' => 'Луганськ',
            'Львівська' => 'Львів',
            'Миколаївська' => 'Миколаїв',
            'Одеська' => 'Одеса',
            'Полтавська' => 'Полтава',
            'Рівненська' => 'Рівне',
            'Сумська' => 'Суми',
            'Тернопільська' => 'Тернопіль',
            'Харківська' => 'Харків',
            'Херсонська' => 'Херсон',
            'Хмельницька' => 'Хмельницький',
            'Черкаська' => 'Черкаси',
            'Чернівецька' => 'Чернівці',
            'Чернігівська' => 'Чернігів'
        ];
    }
}
