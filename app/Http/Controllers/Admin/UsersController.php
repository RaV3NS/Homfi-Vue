<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\Update;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get users list
     *
     * @return Response
     */
    public function searchAjax(Request $request)
    {
        $users = User::query();
        if ($request->has('q')) {
            $users = $users->where('name', 'like',
                '%' . $request->query('q') . '%');
            $users = $users->orWhere('email', 'like',
                '%' . $request->query('q') . '%');
        }

        return response()->json(['results' => $users->get()], 200);
    }

    /**
     * Get users list
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function listAjax(Request $request)
    {
        $users = User::query()->withCount('adverts');

        if($request->has('lastLoginAfter')) {
            $dateAfter = date('Y-m-d', strtotime($request->get('lastLoginAfter')));
            $users->where('last_login', '>=', $dateAfter);
        }

        if($request->has('lastLoginBefore')) {
            $dateBefore = date('Y-m-d', strtotime($request->get('lastLoginBefore')) + 86400);
            $users->where('last_login', '<=', $dateBefore)->whereNotNull('last_login');
        }

        $statuses = [];
        foreach($users->get() as $user) {
            $statuses[$user->status] = $user->status;
        }

        return DataTables::eloquent($users)
            ->addColumn('action', function ($user) {
                $buttons = [
                    [
                        'route' => route('admin.users.show', [$user->id]),
                        'class' => 'primary',
                        'glyphicon' => 'show',
                        'name' => 'show'
                    ],
                ];

                if ($user->status === User::STATUS_ACTIVE) {
                    $buttons[] = [
                        'route' => '#',
                        'class' => 'danger block',
                        'glyphicon' => 'check',
                        'name' => 'block',
                        'method' => 'update'
                    ];
                }

                if ($user->status === User::STATUS_BLOCKED) {
                    $buttons[] = [
                        'route' => '#',
                        'class' => 'success unblock',
                        'glyphicon' => 'check',
                        'name' => 'unblock',
                        'method' => 'update'
                    ];
                }
                if ($user->status === User::STATUS_DISABLED) {
                    $buttons[] = [
                        'route' => '#',
                        'class' => 'warning activate',
                        'glyphicon' => 'check',
                        'name' => 'activate',
                        'method' => 'update'
                    ];
                }

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
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return View
     */
    public function edit(User $user)
    {
        $user->load('phones');

        return view('admin.users.profile', ['user' => $user]);
    }

    /**
     * Show the specified resource.
     *
     * @param User $user
     *
     * @return View
     */
    public function show(User $user)
    {
        $user->load(['phones', 'adverts', 'history', 'history.author']);

        return view('admin.users.profile', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     *
     * @return Redirector
     * @throws \Throwable
     */
    public function update(Update $request, User $user)
    {
        try {
            $validated = $request->validated();
            $user->update($validated);

            if ($user->adverts->count()) {
                $user->manageAdvertsForStatus($request->get('status'));
            }

            $user_log['title'] = isset($validated['user_log']['title']) ? $validated['user_log']['title'] : '';
            $user_log['body'] = isset($validated['user_log']['body']) ? $validated['user_log']['body'] : '';
            $user_log['user_id'] = $user->id;
            $user_log['admin_id'] = Auth::id();
            $user_log['type'] = $validated['status'] ?: '';
            $user->history()->create($user_log);

            $messageType = 'success';
            $messageText = trans('adminlte::admin.user_updated_successfully');
        } catch (Exception $e) {
            $messageType = 'error';
            $messageText = trans('adminlte::admin.cant_update_user');
            Log::error($e->getMessage());
        }

        return redirect()->back()->with($messageType, $messageText);
    }

    /**
     * Read candidate snooze notification
     *
     * @param $id
     *
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function readNotification($id)
    {
        $notification = request()->user()->unreadNotifications->firstWhere('id',
            '=', $id);
        if ($notification) {
            $notification->markAsRead();
            request()->session()->forget('notifications.' . $id);
        } else {
            return response()->json(['status' => 'not found'], 404);
        }

        if (request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return redirect(route('candidates.show',
            ['id' => $id]))->with('success', 'Notification has been read');
    }
}
