<?php

namespace App\Filters;

use Illuminate\Support\Carbon;

class AdvertFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['type', 'price_month', 'room_count', 'publish_date'];

    /**
     * Filter the query by a given type.
     *
     * @param  string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function type($type)
    {
        return $this->builder->whereIn('adverts.type', $type);
    }

    protected function price_month($price)
    {
        if(isset($price['from'])) {
            $this->builder->where('adverts.price_month', '>=', $price['from']);
        }

        if(isset($price['to'])) {
            $this->builder->where('adverts.price_month', '<=', $price['to']);
        }

        return $this->builder;
    }

    protected function room_count($room_count)
    {
        return $this->builder->whereIn('adverts.room_count', $room_count);
    }

    protected function publish_date($date)
    {
        switch ($date) {
            case 'today':
                $startDate  = Carbon::now()->format('Y-m-d');
                break;

            case '2_days_ago':
                $startDate  = Carbon::now()->subDays(2)->format('Y-m-d');
                break;

            case '3_days_ago':
                $startDate  = Carbon::now()->subDays(3)->format('Y-m-d');
                break;

            case 'week_ago':
                $startDate  = Carbon::now()->subWeek()->format('Y-m-d');
                break;

            case '2_weeks_ago':
                $startDate  = Carbon::now()->subWeeks(2)->format('Y-m-d');
                break;

            case 'month_ago':
                $startDate  = Carbon::now()->subMonth()->format('Y-m-d');
                break;

            default:
                $startDate = date('Y-m-d', strtotime($date));
        }

        return $this->builder->whereBetween('adverts.updated_at', [$startDate, now()]);
    }
}
