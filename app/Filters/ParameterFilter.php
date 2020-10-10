<?php

namespace App\Filters;

use App\Parameter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ParameterFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'total_space',
        'living_space',
        'kitchen_space',
        'build_year',
        'height',
        'floor',
        'total_floors',
        'not_first_floor',
        'not_last_floor'
    ];

    /**
     * Filter the query by a given area.
     *
     * @param $total_area
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function total_space($total_area)
    {
        $parameter = Parameter::where('key', 'total_space')->first();

        $this->builder->where(function ($queryId) use ($parameter, $total_area) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($total_area['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$total_area['from']);
            }

            if (!empty($total_area['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$total_area['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given area.
     *
     * @param $living_space
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function living_space($living_space)
    {
        $parameter = Parameter::where('key', 'living_space')->first();

        $this->builder->where(function ($queryId) use ($parameter, $living_space) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($living_space['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$living_space['from']);
            }

            if (!empty($living_space['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$living_space['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given area.
     *
     * @param $kitchen_space
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function kitchen_space($kitchen_space)
    {
        $parameter = Parameter::where('key', 'kitchen_space')->first();

        $this->builder->where(function ($queryId) use ($parameter, $kitchen_space) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($kitchen_space['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$kitchen_space['from']);
            }

            if (!empty($kitchen_space['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$kitchen_space['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $build_year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function build_year($build_year)
    {
        $parameter = Parameter::where('key', 'build_year')->first();

        $this->builder->where(function ($queryId) use ($parameter, $build_year) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($build_year['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$build_year['from']);
            }

            if (!empty($build_year['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$build_year['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $height
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function height($height)
    {
        $parameter = Parameter::where('key', 'height')->first();

        $this->builder->where(function ($queryId) use ($parameter, $height) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($height['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$height['from']);
            }

            if (!empty($height['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$height['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $floor
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function floor($floor)
    {
        $parameter = Parameter::where('key', 'floor')->first();

        $this->builder->where(function ($queryId) use ($parameter, $floor) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($floor['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$floor['from']);
            }

            if (!empty($floor['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$floor['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $total_floors
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function total_floors($total_floors)
    {
        $parameter = Parameter::where('key', 'total_floors')->first();

        $this->builder->where(function ($queryId) use ($parameter, $total_floors) {
            $query = DB::table('advert_parameter')->where('advert_parameter.parameter_id', $parameter->id);

            if (!empty($total_floors['from'])) {
                $query->where('advert_parameter.value', '>=', (int)$total_floors['from']);
            }

            if (!empty($total_floors['to'])) {
                $query->where('advert_parameter.value', '<=', (int)$total_floors['to']);
            }

            $valueIds = $query->pluck('advert_id');

            $haveNoValueIds = DB::table('advert_parameter')
                ->selectRaw('DISTINCT advert_id')
                ->whereNotIn('advert_id',
                    DB::table('advert_parameter')
                        ->select('advert_id')
                        ->where('parameter_id',  $parameter->id)
                )
                ->pluck('advert_id');

            $advertIds = $valueIds->merge($haveNoValueIds);

            $queryId->whereIn('advert_parameter.advert_id', $advertIds->all());
        });

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $joint_rent
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function not_first_floor()
    {
        $this->floor(["from" => 2]);

        return $this->builder;
    }

    /**
     * Filter the query by a given data.
     *
     * @param $joint_rent
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function not_last_floor()
    {
        $floor = Parameter::where('key', 'floor')->first();
        $total_floors = Parameter::where('key', 'total_floors')->first();
        $lastFloorsAdverts = DB::table('advert_parameter', 'ap')->select('ap.advert_id')
            ->join('advert_parameter as ap2', 'ap.advert_id', '=', 'ap2.advert_id')
            ->where('ap2.parameter_id', $total_floors->id)
            ->where('ap.parameter_id', $floor->id)
            ->where('ap2.value', '>', '1')
            ->whereRaw('`ap`.`value` = `ap2`.`value`')
            ->pluck('advert_id');

        $this->builder->whereNotIn('adverts.id', $lastFloorsAdverts);

        return $this->builder;
    }
}
