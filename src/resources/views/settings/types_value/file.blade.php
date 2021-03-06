<div class="form-group {{ $errors->has('value') ? ' has-error' : '' }}">
    <label class="control-label col-md-2" for="value">Value <sup class="text-danger">*</sup></label>
    <div class="col-md-10">
        <input class="form-control" name="value" id="value" type="file" placeholder="Value"/>
        @if(!empty($setting->value))
            <a style="margin-bottom: 5px;" class="btn btn-info"
               href="{{ url(config('settings.route_prefix').'/download/'.$setting->id) }}"
               target="_blank">
                <i class="fa fa-download"></i> Download {{ $setting->getOriginal('value') }}
            </a>
        @endif
    </div>
</div>