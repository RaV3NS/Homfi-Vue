<?php

namespace App\Http\Controllers\Admin;

use App\Administrative;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Administrative\Create;
use App\Http\Requests\Admin\Administrative\Update;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdministrativeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.administrative.index');
    }

    /**
     * Get adverts list
     *
     * @throws \Exception
     */
    public function listAjax()
    {
        $administrative = Administrative::query()
            ->selectRaw('administrative.*, `cities`.`name_ru` as `city_name`')
            ->leftJoin('cities', function (JoinClause $join) {
                $join->on('cities.id', '=', 'administrative.city_id');
            })->withTrashed();


        return DataTables::eloquent($administrative)
            ->addColumn('action', function ($administrative) {
                $buttons = [[
                    'route' => route('admin.administrative.edit', $administrative->id),
                    'class' => 'warning',
                    'glyphicon' => 'edit',
                    'name' => 'edit',
                ]];
                if ($administrative->trashed()) {
                    $buttons[] = [
                        'route' => route('admin.administrative.destroy', $administrative->id),
                        'class' => 'success',
                        'glyphicon' => 'delete',
                        'confirm' => 'confirm_show_admin',
                        'name' => 'show_admin',
                        'method' => 'delete'
                    ];
                } else {
                    $buttons[] = [
                        'route' => route('admin.administrative.destroy', $administrative->id),
                        'class' => 'danger',
                        'glyphicon' => 'delete',
                        'confirm' => 'confirm_hide',
                        'name' => 'hide',
                        'method' => 'delete'
                    ];
                }
                return view('admin.partials.buttons')->withButtons($buttons)->render();
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.administrative.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $administrative = Administrative::query()->create($request->all());

        return redirect()->route('admin.administrative.index');
    }

    /**
     * @param Administrative $administrative
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $administrative = Administrative::query()->withTrashed()->findOrFail($id);

        return view('admin.administrative.edit', ['administrative' => $administrative]);
    }

    /**
     * @param Update $request
     * @param Administrative $administrative
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, $id)
    {
        $administrative = Administrative::query()->withTrashed()->findOrFail($id);
        $administrative->fill($request->all());
        $administrative->save();

        return redirect()->route('admin.administrative.index');
    }

    /**
     * @param Administrative $administrative
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy($id)
    {
        $administrative = Administrative::query()->withTrashed()->findOrFail($id);

        if ($administrative->trashed()) {
            $administrative->restore();
        } else {
            $administrative->delete();
        }

        return redirect()->back();
    }
}
