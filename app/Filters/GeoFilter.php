<?php

namespace App\Filters;

class GeoFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['street', 'administrative', 'subway'];

    /**
     * Filter the query by a given id.
     *
     * @param  string $street
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function street($street)
    {
        return $this->builder->where('adverts.street_id', $street);
    }

    protected function administrative($administrative)
    {
        return $this->builder->where('adverts.administrative_id', $administrative);
    }

    protected function subway($subway)
    {
        return $this->builder->where('adverts.subway_id', $subway);
    }
}
