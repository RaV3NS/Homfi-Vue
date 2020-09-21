<?php

namespace App\Http\Controllers\Admin;

use App\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.notifications.index');
    }

    /**
     * Get adverts list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function listAjax(Request $request)
    {
        $notifications = AdminNotification::query();

        if($request->has('filterDateAfter')) {
            $dateAfter = date('Y-m-d', strtotime($request->get('filterDateAfter')));
            $notifications->where('created_at', '>=', $dateAfter);
        }

        if($request->has('filterDateBefore')) {
            $dateBefore = date('Y-m-d', strtotime($request->get('filterDateBefore')) + 86400);
            $notifications->where('created_at', '<=', $dateBefore)->whereNotNull('created_at');
        }

        $statuses = $types = [];
        foreach($notifications->get() as $notification) {
            $statuses[$notification->status] = $notification->status;
            $types[$notification->type] = $notification->type;
        }

        return DataTables::eloquent($notifications)
            ->with(['statuses' => $statuses, 'types' => $types])
            ->make(true);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdminNotification  $adminNotification
     * @return \Illuminate\Http\Response
     */
    public function show(AdminNotification $adminNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdminNotification  $adminNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminNotification $adminNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdminNotification  $adminNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminNotification $adminNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdminNotification  $adminNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminNotification $adminNotification)
    {
        //
    }
}
