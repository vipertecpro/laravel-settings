<div class="form-group {{ $errors->has('value') ? ' has-error' : '' }}">
    <label class="control-label col-md-2" for="value">Value <sup class="text-danger">*</sup></label>
    <div class="col-md-10">
        <input class="form-control datepicker" name="value" id="value" type="text" placeholder="Value"
               value="{{ old('value',$setting->value) }}"/>
    </div>
</div>