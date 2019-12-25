@extends('settings::layout.settings')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3><i class="fa fa-plus-square-o" aria-hidden="true"></i> Add New Setting</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!!
                Form::open([
                    'route' => Config::get('settings.route-as').'store',
                    'class' => 'form-horizontal',
                    'files' => true
                ])
            !!}
                <input type="hidden" name="type" value="{{ $type }}"/>
                @include('settings::shared_input')
                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <a href="{{ url(Config::get('settings.route_prefix')) }}" class="btn btn-light btn-sm">
                            <i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-save" aria-hidden="true"></i> Save & Continue
                        </button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection