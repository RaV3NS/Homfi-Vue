<?php

namespace App\Traits;

use App\Administrative;
use App\Street;
use App\Subway;

trait Seo
{
    public $meta = ['title', 'meta_description', 'h1'];

    protected $valuesKeys = [
        'type' => [
            'flat',
            'house',
            'half-house',
            'room'
        ],
        'room_count' => [
            1, 2, 3, 4,
        ],
        'price_month' => [
            'from' => 'от {pricemin}',
            'to' => 'до {pricemax}',
        ],
        'street' => 'улица {value}',
        'administrative' => 'район {value}',
        'subway' => 'метро {value}',
    ];

    protected $strictParameters = [
        'room_count',
        'type',
        'street',
        'administrative',
        'subway',
        'price_month'
    ];

    public $replaces = [
        'city' => '',
        'count' => '',
        'type' => '',
        'address' => '',
        'pricemin' => '',
        'from_price' => '',
        'offer_name' => '',
        'offer_description' => '',
        'image_number' => ''
    ];

    public $activeFilters = [];

    public function setReplaces($replaces)
    {
        foreach ($replaces as $key => $value) {
            $this->replaces[$key] = $value;
        }
    }

    public function setFilters($filters)
    {
        foreach ($filters as $key => $value) {
            if (in_array($key, $this->strictParameters)) {
                $this->activeFilters[$key] = $value;
            }
        }

        foreach ($this->activeFilters as $activeFilter => $activeValue) {
            if (is_array($this->valuesKeys[$activeFilter])) {
                if ($activeFilter == 'price_month') {
                    $this->replaces['pricemin'] = $activeValue['from'] ?? '';
                    $this->replaces['pricemax'] = $activeValue['to'] ?? '';
                } elseif ($activeFilter == 'room_count') {
                    foreach ($activeValue as $value) {
                        $this->replaces['filter'][] = trans('parameter_values.' . $activeFilter . '.filter.' . $value);
                    }
                } elseif ($activeFilter == 'type') {
                    foreach ($activeValue as $value) {
                        $this->replaces['filter'][] = trans('parameter_values.' . $activeFilter . '.filter.' . $value);
                    }
                } else {
                    $activeValue = reset($activeValue);
                    $this->replaces[$activeFilter] = trans('parameter_values.' . $activeFilter . '.filter.' . $activeValue);
                    $this->replaces['filter'][] = trans('parameter_values.' . $activeFilter . '.filter.' . $activeValue);
                }
            } else {
                if (in_array($activeFilter, ['administrative', 'street', 'subway'])) {

                    if ($activeFilter == 'administrative') {
                        $this->replaces['filter'][] = Administrative::find((int)$activeValue)->name;
                    }
                    if ($activeFilter == 'street') {
                        $this->replaces['filter'][] = trans('seo_templates.street') . Street::find((int)$activeValue)->name;
                    }
                    if ($activeFilter == 'subway') {
                        $this->replaces['filter'][] = trans('seo_templates.subway') . Subway::find((int)$activeValue)->name;
                    }
                } else {
                    $this->replaces['filter'][] = $this->valuesKeys[$activeFilter];
                    $this->replaces[$activeFilter] = $this->valuesKeys[$activeFilter][$activeValue];
                }
            }
        }
        
        $filter = '';
        if(!empty($this->replaces['filter'])){
            $filter = join(' ', $this->replaces['filter']);
        }

        $this->replaces['filter'] = $filter;
    }

    public function advertCityTemplate()
    {
        foreach ($this->meta as $tag) {
            $$tag = trans('seo_templates.advertCityTemplate.' . $tag);
        }

        return $this->getTemplate(compact($this->meta));
    }

    public function advertFilterTemplate()
    {
        foreach ($this->meta as $tag) {
            $$tag = trans('seo_templates.advertFilterTemplate.' . $tag);
        }

        return $this->getTemplate(compact($this->meta));
    }

    public function advertPageTemplate($advert)
    {
        $this->replaces['type'] = trans('parameter_values.type.' . $advert->type);
        $this->replaces['advert_id'] = $advert->id;
        $this->replaces['city'] = $advert->city->name;
        $this->replaces['address'] = $advert->address;

        foreach ($this->meta as $tag) {
            $$tag = trans('seo_templates.advertPageTemplate.' . $tag);
        }

        $result = compact($this->meta);
        if(!empty($advert->images)) {
            foreach ($advert->images as $index => $image) {
                $this->replaces['image_number'] = $index + 1;
                $result['images'][$index] = $this->advertImageAlt();
            }
        }

        return $this->getTemplate($result);
    }

    public function advertImageAlt()
    {
        $alt = "{type} {advert_id}, {address} ({city}) - {image_number}";

        return $this->getTemplate(compact('alt'));
    }

    protected function getTemplate($meta_tags)
    {
        $result = $replaces = [];
        foreach ($this->replaces as $key => $replace) {
            $replaces['{' . $key . '}'] = $replace;
        }

        foreach ($meta_tags as $key => $value) {
            $result[$key] = str_replace(array_keys($replaces), array_values($replaces), $value);
        }

        return $result;
    }
}
