<?php

namespace App\Filters;

use App\Option;

class OptionFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'joint_rent'
    ];

    /**
     * Filter the query by a given data.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function joint_rent()
    {
        $option = Option::where('key', 'joint_rent')->first();

        $this->builder->where('advert_option.option_id', $option->id);

        return $this->builder;
    }
}
