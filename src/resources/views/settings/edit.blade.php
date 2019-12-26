@extends('settings::layout.settings')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Setting</h3>
        </div>
    </div>
    <div class="row">
        {!!
            Form::open([
                'url'    => url(Config::get('settings.route_prefix').'/update/'.$setting->id),
                'files'  => true,
                'class'  => 'form-horizontal',
                'method' => 'PATCH'
            ])
        !!}
            <div class="col-md-12">
                @include('settings::shared_input')
            </div>
            <div class="col-md-12">
                @include('settings::types_value.'.strtolower($setting->type))
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2 text-right">
                        <a href="{{ url(Config::get('settings.route_prefix')) }}" class="btn btn-light btn-sm">
                            <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                            Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-save" aria-hidden="true"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            let editor = document.querySelector('.ck-editor');
            if (editor !== null) {
                CKEDITOR.replace(editor, {});
                CKEDITOR.instances['value'].on('change', function () {
                    CKEDITOR.instances['value'].updateElement()
                });
                CKEDITOR.config.allowedContent = true;
            }

            $('.datepicker').datepicker({
                format: '{{ Config::get('settings.date_format') }}',
                orientation: "bottom auto"
            });

            if ($("#values-table").length > 0) {
                $(document).on('click', '#add-value', function () {
                    let index = $('#values-table tr:last').data('index');
                    if (isNaN(index)) {
                        index = 0;
                    } else {
                        index++;
                    }
                    $('#values-table tr:last').after('<tr id="tr_' + index + '" data-index="' + index + '"><td>' +
                        '<input name="{{ $setting->code }}[' + index + '][key]" type="text"' +
                        'value="" class="form-control"/></td><td>' +
                        '<input name="{{ $setting->code }}[' + index + '][value]" type="text"' +
                        'value="" class="form-control"/></td>' +
                        '<td><button type="button" class="btn btn-danger remove-value" data-index="' + index + '">'
                        + '<i class="fa fa-remove"></i></button></td>' +
                        '</tr>');
                });

                $(document).on('click', '.remove-value', function () {
                    let index = $(this).data('index');
                    $("#tr_" + index).remove();
                });
            }

        });
    </script>
@endsection