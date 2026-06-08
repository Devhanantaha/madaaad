{{-- Header Option Header --}}
<h3 class="black mb-3">{{ translate('Header Top') }}</h3>
<input type="hidden" name="option_name" value="header_top">



<div class="form-group row py-4 border-bottom">
    <div class="col-xl-4 mb-3 mb-3">
        <label class="font-16 bold black">{{ translate('Header Top') }}
        </label>
        <span class="d-block">{{ translate('Enable / Disable Header Top') }}</span>
    </div>
    <div class="col-xl-6 offset-xl-1">
        <label class="switch success">
            <input type="hidden" name="header_top_status" value="0">
            <input type="checkbox"
                {{ isset($option_settings['header_top_status']) && $option_settings['header_top_status'] == 1 ? 'checked' : '' }}
                name="header_top_status" id="custom_header" value="1">
            <span class="control" id="header_top_switch">
                <span class="switch-off">Disable</span>
                <span class="switch-on">Enable</span>
            </span>
        </label>
    </div>
</div>

<div class="form-group row py-4 border-bottom">
    <div class="col-xl-4 mb-3 mb-3">
        <label class="font-16 bold black">{{ translate('Language Dropdown') }}
        </label>
        <span class="d-block">{{ translate('Enable / Disable Language Dropdown') }}</span>
    </div>
    <div class="col-xl-6 offset-xl-1">
        <label class="switch success">
            <input type="hidden" name="language_dropdown_status" value="0">
            <input type="checkbox"
                {{ isset($option_settings['language_dropdown_status']) && $option_settings['language_dropdown_status'] == 1 ? 'checked' : '' }}
                name="language_dropdown_status" id="language_dropdown_status" value="1">
            <span class="control" id="language_dropdown_switch">
                <span class="switch-off">Disable</span>
                <span class="switch-on">Enable</span>
            </span>
        </label>
    </div>
</div>

<div class="form-group row py-4 border-bottom">
    <div class="col-xl-4 mb-3 mb-3">
        <label class="font-16 bold black">{{ translate('Currency Dropdown') }}
        </label>
        <span class="d-block">{{ translate('Enable / Disable Currency Dropdown') }}</span>
    </div>
    <div class="col-xl-6 offset-xl-1">
        <label class="switch success">
            <input type="hidden" name="currency_dropdown_status" value="0">
            <input type="checkbox"
                {{ isset($option_settings['currency_dropdown_status']) && $option_settings['currency_dropdown_status'] == 1 ? 'checked' : '' }}
                name="currency_dropdown_status" id="currency_dropdown_status" value="1">
            <span class="control" id="currency_dropdown_switch">
                <span class="switch-off">Disable</span>
                <span class="switch-on">Enable</span>
            </span>
        </label>
    </div>
</div>
