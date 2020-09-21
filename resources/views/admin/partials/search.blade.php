<!-- search form -->
<div class="sidebar-form">
    <div class="input-group">
        <select id="search-input" class="form-control">
        </select>
    </div>
</div>
<!-- /.search form -->
@section('adminlte_js')
    @parent
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        function searchRender(data, container) {
            if (data.archived) {
                $(container).addClass('archived');
            }

            return $('<span>' + data.text + (data.snoozed ? '<i class="fa fa-bell"></i>' : '') + '</span>');
        }

        $(function () {
            $('#search-input').select2({
                placeholder: 'Search...',
                templateResult: searchRender,
                ajax: {
                    url: "{{ url('autocomplete') }}",
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    delay: 250,
                    width: 'element',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name + (item.job_title ? ', ' + item.job_title : ''),
                                    archived: !item.is_active,
                                    snoozed: item.is_snoozed
                                }
                            })
                        };
                    }
                },
            }).on('select2:select', function (e) {
                location.href = candidateProfileUrl + e.params.data.id;
            });
            var candidateProfileUrl = "{{route('candidates.show', ['id' => 0])}}".replace(/^(.*\/)[^\/]+$/ig, '$1');
        });
    </script>
@stop
