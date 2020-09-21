<div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="activateModalLabel"><span id="count-block"><span
                                id="adverts-count"></span> {{ __('adminlte::admin.advert.reject_description') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" value="{{\App\Advert::STATUS_REJECTED}}"/>

                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio1" type="radio"
                                   name="user_log[title]"
                                   value="want_money" checked="checked">
                            <label for="rejectRadio1"
                                   class="custom-control-label">{{ __('adminlte::admin.user.reason.want_money') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio2" type="radio"
                                   name="user_log[title]"
                                   value="bad_photos">
                            <label for="rejectRadio2"
                                   class="custom-control-label ">{{ __('adminlte::admin.advert.reason.bad_photos') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio3" type="radio"
                                   name="user_log[title]"
                                   value="object_done">
                            <label for="rejectRadio3"
                                   class="custom-control-label">{{ __('adminlte::admin.advert.reason.object_done') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio4" type="radio"
                                   name="user_log[title]"
                                   value="fraud">
                            <label for="rejectRadio4"
                                   class="custom-control-label">{{ __('adminlte::admin.advert.reason.fraud') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio5" type="radio"
                                   name="user_log[title]"
                                   value="contacts_dont_respond">
                            <label for="rejectRadio5"
                                   class="custom-control-label">{{ __('adminlte::admin.advert.reason.contacts_dont_respond') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" id="rejectRadio6" type="radio"
                                   name="user_log[title]"
                                   value="another">
                            <label for="rejectRadio6"
                                   class="custom-control-label">{{ __('adminlte::admin.advert.reason.another') }}</label>
                        </div>
                        <div class="custom-control form-group">
                            <input class="form-control user-reason--another-field" name="user_log[body]"
                                   placeholder="{{ __('adminlte::admin.advert.reason.another_placeholder') }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input has-required-field" id="rejectRadio7" type="radio"
                                   name="user_log[title]"
                                   data-required-field="required-comment"
                                   value="wrong_contacts">
                            <label for="rejectRadio7"
                                   class="custom-control-label">{{ __('adminlte::admin.advert.reason.wrong_contacts') }}</label>
                        </div>
                        <div class="custom-control form-group">
                            <textarea data-required="required-comment" class="form-control user-reason--comment"
                                      name="user_log[body]"
                                      placeholder="{{ __('adminlte::admin.advert.reason.comment') }}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ __('adminlte::admin.button.cancel') }}</button>
                    <button type="submit"
                            class="btn btn-danger">{{ __('adminlte::admin.button.reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    @parent
    <script>
        $('#reject-modal input[type="radio"]').on('change', function () {
            me = $(this);
            if (me.hasClass('has-required-field')) {
                let field = me.data('required-field');
                if (field) {
                    $('[data-required="' + field + '"]').attr('required', !!me.prop('checked')).attr('disabled', !me.prop('checked'));
                }
            } else {
                me.closest('form').find('[data-required]').attr('required', false).attr('disabled', true);
            }
        });
    </script>
@stop
