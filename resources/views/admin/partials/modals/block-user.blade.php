<div class="modal fade" id="block-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"><span id="count-block"><span
                                id="adverts-count"></span> {{ __('adminlte::admin.block_user_description') }}?</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ __('adminlte::admin.button.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" value="{{ \App\User::STATUS_BLOCKED }}"/>

                    <h5>{{ __('adminlte::admin.user.blocked_reason') }}</h5>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="customRadio1" type="radio" name="user_log[title]"
                                   value="{{ __('adminlte::admin.user.reason.want_money') }}" checked="checked">
                            <label for="customRadio1"
                                   class="custom-control-label">{{ __('adminlte::admin.user.reason.want_money') }}</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="customRadio2" type="radio" name="user_log[title]"
                                   value="{{ __('adminlte::admin.user.reason.wrong_info') }}"/>
                            <label for="customRadio2"
                                   class="custom-control-label">{{ __('adminlte::admin.user.reason.wrong_info') }}</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="customRadio3" type="radio" name="user_log[title]"
                                   value="{{ __('adminlte::admin.user.reason.mass_complains') }}"/>
                            <label for="customRadio3"
                                   class="custom-control-label">{{ __('adminlte::admin.user.reason.mass_complains') }}</label>
                        </div>
                        <div class="custom-control custom-radio user--another-reason">
                            <input class="custom-control-input has-required-field" id="customRadio4" type="radio"
                                   name="user_log[title]" value="{{ __('adminlte::admin.user.reason.another_reason') }}"
                                   data-required-field="required-comment"/>
                            <label for="customRadio4"
                                   class="custom-control-label">{{ __('adminlte::admin.user.reason.another_reason') }}</label>
                        </div>
                        <div class="custom-control form-group">
                            <input class="form-control user-reason--another-field hidden-comments-block"
                                   name="user_log[title]"
                                   data-required="required-comment"
                                   disabled="disabled"
                                   placeholder="{{ __('adminlte::admin.user.reason.another_placeholder') }}"/>
                            <div class="">
                                Комментарий:
                                <textarea class="form-control another-reason--comment"
                                          name="user_log[body]" placeholder="Комментарий"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ __('adminlte::admin.button.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('adminlte::admin.button.block') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    @parent

    <script>
        $('#block-modal input[type="radio"]').on('change', function () {
            me = $(this);
            if (me.hasClass('has-required-field')) {
                let field = me.data('required-field');
                if (field) {
                    $('[data-required="' + field + '"]').attr('required', !!me.prop('checked')).attr('disabled', !me.prop('checked'));
                    $('#block-modal .hidden-comments-block').show();
                }
            } else {
                $('#block-modal .hidden-comments-block').hide();
                me.closest('form').find('[data-required]').attr('required', false).attr('disabled', true);
            }
        });
    </script>
@stop
