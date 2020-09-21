<?php

namespace App\Http\Controllers\Admin;

use App\AdminNotification;
use App\Advert;
use App\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Advert\Update;
use App\Http\Requests\Admin\Advert\UpdateStatus;
use App\Jobs\GetCoordinates;
use App\Option;
use App\OsmQueue;
use App\Parameter;
use App\Phone;
use App\Street;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AdvertsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.adverts.index');
    }

    /**
     * Get users list
     *
     * @return Response
     */
    public function searchAjax(Request $request)
    {
        $users = Advert::query();
        if ($request->has('q')) {
            $users = $users->where('name', 'like',
                '%' . $request->query('q') . '%');
            $users = $users->orWhere('email', 'like',
                '%' . $request->query('q') . '%');
        }

        return response()->json(['results' => $users->get()], 200);
    }

    /**
     * Get adverts list
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function listAjax(Request $request)
    {
        $adverts = Advert::query()
            ->select(['adverts.*'])
            ->with(['city', 'city.region', 'city.region.district', 'administrative', 'street']);

        if ($request->has('cityId') AND !empty($request->get('cityId'))) {
            $adverts->where('city_id', $request->get('cityId'));
        }

        if ($request->has('user_id')) {
            $adverts->where('user_id', '=', $request->get('user_id'));
        }

        $cities = $types = $statuses = [];
        foreach ($adverts->get() as $advert) {
            $cities[$advert->city->id] = $advert->city->name_ru . ' ' . $advert->city->region->name_ru . ' район';
            $types[$advert->type] = $advert->type;
            $statuses[$advert->status] = $advert->status;
        }

        return DataTables::eloquent($adverts)
            ->addColumn('action', function ($advert) {
                $buttons = [
                    [
                        'route' => route('admin.adverts.show', [$advert->id]),
                        'class' => 'primary',
                        'glyphicon' => 'show',
                        'name' => 'show'
                    ],
                ];

                if ($advert->status === Advert::STATUS_BLOCKED) {
                    $buttons[] = [
                        'route' => '#',
                        'class' => 'success enable',
                        'glyphicon' => 'check',
                        'name' => 'unblock',
                        'method' => 'update'
                    ];
                }

                if ($advert->status !== Advert::STATUS_DRAFT AND $advert->status !== $advert::STATUS_BLOCKED) {
                    $buttons[] = [
                        'route' => '#',
                        'class' => 'danger block',
                        'glyphicon' => 'check',
                        'name' => 'block',
                        'method' => 'update'
                    ];
                }

                return view('admin.partials.buttons')->withButtons($buttons)
                    ->render();
            })
            ->with(['cities' => $cities, 'types' => $types, 'statuses' => $statuses])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function show(Advert $advert)
    {
        $advert->load(['administrative', 'phones', 'complains', 'parameters', 'options', 'history']);
        $advert->parameters->append('value');

        //dd($advert);

        $advert->newAdminNotifications()->each(function (AdminNotification $notification) {
            $notification->update(['status' => 'read']);
        });

        return view('admin.adverts.show', ['advert' => $advert]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function edit(Advert $advert)
    {
        $advert->editing = 1;
        $advert->save();

        $advert->parameters->append('value');
        foreach ($advert->parameters()->get() as $adParam) {
            $advertParameters[$adParam->key] = $adParam;
        }

        $advert->load(['options']);
        foreach ($advert->options()->get() as $adOption) {
            $advertOptions[$adOption->key] = $adOption;
        }

        $allParameters = Parameter::all();
        $allOptions = Option::all();

        return view('admin.adverts.edit', compact('advert', 'allParameters', 'allOptions', 'advertParameters', 'advertOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param  \App\Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Advert $advert)
    {
        $validated = $request->validated();

        if($advert->street_id != $validated['street_id']) {
            $osmQueue = $this->getNewCoordinates($validated);
            if (isset($osmQueue) && $osmQueue) {
                $osmQueue->delete();
                $validated['lat'] = $osmQueue->lat;
                $validated['lng'] = $osmQueue->lng;
            }
        }

        $advert->update($validated);
        $phones = $request->only('phone');

        foreach (reset($phones) as $id => $phone) {
            $Phone = Phone::findOrFail($id);
            $Phone->update($phone);
        }

        $parameters = $request->get('parameters');
        $advertParameters = [];

        DB::table('advert_parameter')->where('advert_id', $advert->id)->delete();
        if (!empty($parameters)) {
            foreach ($parameters as $parameterId => $value) {
                $parameterModel['parameter_id'] = $parameterId;
                $parameterModel['advert_id'] = $advert->id;
                $parameterModel['value'] = $value;

                if (!empty($parameterModel['value'])) {
                    if ($parameterId == 1) {
                        $advertParameters['type'] = $parameterModel['value'];
                    }
                    if ($parameterId == 2) {
                        $advertParameters['room_count'] = $parameterModel['value'];
                    }

                    DB::table('advert_parameter')->insertOrIgnore($parameterModel);
                }
                unset($parameterModel, $parameter, $Parameter);
            }

            $advertParameters['price_month'] = $request->get('price_month');
            $advertParameters['body'] = $request->get('body') ?? '';

            $advert->update($advertParameters);
        }

        $options = $request->get('options');
        DB::table('advert_option')->where('advert_id', $advert->id)->delete();

        if (!empty($options)) {
            foreach ($options as $option_id => $value) {
                $optionModel['option_id'] = $option_id;
                $optionModel['advert_id'] = $advert->id;
                DB::table('advert_option')->insertOrIgnore($optionModel);
            }
        }

        $advert->editing = 0;
        $advert->save();

        return redirect(route('admin.adverts.show', $advert->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStatus $request
     * @param  \App\Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(UpdateStatus $request, Advert $advert)
    {
        $validated = $request->validated();
        $advertUpdate = ['status' => $validated['status']];

        if ($request->has('publish_date')) {
            $advertUpdate['publish_date'] = date('Y-m-d H:i:s', time());
        }

        $advert->update($advertUpdate);
        if ($advert->user AND $request->has('user_log')) {
            $user_log = $request->get('user_log');
            $advert->user->history()->create([
                'admin_id' => Auth::id(),
                'advert_id' => $advert->id,
                'type' => 'advert_' . $validated['status'],
                'title' => $user_log['title'],
                'body' => $user_log['body'] ?? ''
            ]);

            $advert->user->notifications()->create([
                'user_id' => $advert->user->id,
                'advert_id' => $advert->id,
                'type' => 'advert_' . $validated['status'],
                'title' => $user_log['title'],
                'body' => $user_log['body'] ?? ''
            ]);
        }

        return back();
    }

    /**
     * @param array $validated
     * @return mixed
     */
    public function getNewCoordinates(array $validated)
    {
        $city = City::query()->with(['region.district'])->findOrFail($validated['city_id']);
        $street = Street::query()->select('id', 'name_uk', 'prefix')->findOrFail($validated['street_id']);
        $uuid = Str::uuid();
        $address = preg_replace('/^\D+/u', '', $validated['address']);
        $prefix = Street::getNormalizePrefix($street->prefix);
        $streetString = join(' ', [$street->getRawOriginal('name_uk'), $prefix]);
        GetCoordinates::dispatch($city->name_uk, $streetString, $address, $uuid,
            GetCoordinates::PHASE_WITH_LETTER, $street->id, app()->getLocale())->onQueue('coordinates');

        $i = 40;
        while ($i > 0) {
            $osmQueue = OsmQueue::query()->firstWhere('uuid', '=', $uuid);
            if ($osmQueue) {
                break;
            } else {
                $i--;
                usleep(250000);
            }
        }
        return $osmQueue;
    }

    public function finishEditing(int $advertId)
    {
        return DB::table('adverts')->where('id', $advertId)->update(['editing' => 0]);
    }
}
