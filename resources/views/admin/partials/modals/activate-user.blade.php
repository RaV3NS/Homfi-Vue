<div class="modal fade" id="activate-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="activateModalLabel"><span id="count-block"><span
                                id="adverts-count"></span> {{ __('adminlte::admin.activate_user_description') }}?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" value="{{\App\User::STATUS_ACTIVE}}" />

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('adminlte::admin.button.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('adminlte::admin.button.activate_user') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')

    @parent
    <script>

    </script>
@stop
