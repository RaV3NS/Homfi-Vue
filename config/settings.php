<?php

return [
    'messengers' => [
        'viber',
        'telegram',
        'whatsapp'
    ],
    'user_social_links' => [
        'instagram',
        'facebook',
        'facebook_messenger',
        'email',
        'skype'
    ],
    'currency' => [
        'sign' => 'грн.',
        'symbol' => "₴",
        'iso' => 'UAH'
    ],
    'main_cities' => [
        '5cb61671-749b-11df-b112-00215aee3ebe', //Киев
        '50c5951b-749b-11df-b112-00215aee3ebe', //Днепр
    ],
    'advert_page_parameters' => [
        'type',
        'room_count',
        'total_space',
        'living_space',
        'kitchen_space',
        'floor',
        'total_floor',
        'height',
        'building_year',
        'heating',
        'communals'
    ],
    'additional_filter_parameters' => [
        'joint_rent',
        'publish_date',
        'not_first_floor',
        'not_last_floor',
        'query'
    ],
    'options_categories' => [
        'main' => [
            'autonomous_heading',
            'air_conditioner',
            'furniture',
            'internet',
            'near_subway',
            'tv',
            'parking',
            'many_elevators',
        ],
        'additional' => [
            'water_counter',
            'heading_counter',
            'autonomous_heading',
            'washing_machine',
            'boiler',
            'air_conditioner',
            'two_place_bed',
            'furniture',
            'armour_door',
            'tv',
            'internet',
            'bath',
            'shower',
            'refrigerator',
            'dishwasher',
            'oven',
            'plastic_windows',
            'balcony',
            'many_elevators',
            'split_rooms',
            'fresh_renovation',
        ],
        'infrastructure' => [
            'near_subway',
            'supermarket',
            'park',
            'school',
            'kindergarten',
            'gym',
            'parking',
            'covered_parking',
            'road_view',
            'yard_view',
            'busstops',
        ],
        'household' => [
            'curtains',
            'dishes',
            'vacuum_cleaner',
            'iron',
            'electric_kettle',
        ],
        'other' => [
            'first_rent',
            'with_pets',
            'joint_rent',
        ]
    ],
    'restore_password_url' => env('RESTORE_PASSWORD_ENDPOINT'),

];
