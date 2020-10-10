<?php

namespace App\Http\Controllers\Admin;

use App\Complain;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Complains\Update;
use App\Http\Requests\Admin\Complains\UpdateStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ComplainsController extends Controller
{
    /**
     * Get complains list
     *
     * @return Response
     */
    public function searchAjax(Request $request)
    {
        $complains = Complain::query();
        if ($request->has('q')) {
            $complains = $complains->where('name', 'like',
                '%' . $request->query('q') . '%');
            $complains = $complains->orWhere('email', 'like',
                '%' . $request->query('q') . '%');
        }

        return response()->json(['results' => $complains->get()], 200);
    }

    /**
     * Get complains list
     *
     * @return JsonResponse
     * @throws Exception
     * @throws \Exception
     */
    public function listAjax(Request $request)
    {
        $complains = Complain::query();
        if($request->has('advert_id')){
            $complains->where('advert_id', '=', $request->get('advert_id'));
        }

        $statuses = [];
        foreach($complains->get() as $complain) {
            $statuses[$complain->status] = $complain->status;
        }

        return DataTables::eloquent($complains)
            ->addColumn('action', function ($complain) {
                $buttons = [
                    [
                        'route' => route('admin.complains.show', [$complain->id]),
                        'class' => 'primary',
                        'glyphicon' => 'show',
                        'name' => 'show',
                        'target' => '_blank'
                    ],
                ];

                return view('admin.partials.buttons')->withButtons($buttons)
                    ->render();
            })
            ->with(['statuses' => $statuses])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request)
    {
//    dd(Complain::find(83));
        return view('admin.complains.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Show the specified resource.
     *
     * @param Complain $complain
     *
     * @return View
     */
    public function show(Complain $complain)
    {
        $complain->load(['advert', 'history', 'history.author']);

        return view('admin.complains.show', ['complain' => $complain]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit(Complain $complain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Complain $complain)
    {
        $complain->status = $request->get('status');
        $complain->save();

        $complain->history()->create([
            'status' => $request->get('status'),
            'body' => $request->get('body') ?: '',
            'admin_id' => Auth::id()
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complain $complain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStatus $request
     * @param Complain $complain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(UpdateStatus $request, Complain $complain)
    {
        $validated = $request->validated();
        $complain->update(['status' => $validated['status']]);

        $complain->history()->create([
            'admin_id' => Auth::id(),
            'status' => $complain->status,
            'body' => isset($validated['body']) ? $validated['body'] : '',
        ]);

        return back();
    }
}
