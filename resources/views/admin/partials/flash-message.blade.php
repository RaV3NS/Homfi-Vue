@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        Please check the form below for errors
    </div>
@endif
@if ($notifications = \Illuminate\Support\Facades\Session::get('notifications'))
    @foreach ($notifications as $key => $notification)
        <div class="alert alert-info alert-block candidate-notifications"
             data-url="{{route('users.readNotification', ['id' => $key])}}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong><a href="{{route('candidates.show', ['id' => $notification[0]['candidate_id']])}}">
                    {!! $notification[0]['candidate_name'] !!}</a> is now unsnoozed</strong>
        </div>
    @endforeach
    @push('js')
        <script type="application/javascript" src="{{ asset('js/notifications.js') }}"></script>
    @endpush
@endif


