@php
    $play_store_link = isset($value) && isset($value['play_store_link']) ? $value['play_store_link'] : '';
    $app_store_link = isset($value) && isset($value['app_store_link']) ? $value['app_store_link'] : '';
    $play_store_icon = isset($value) && isset($value['play_store_icon']) ? $value['play_store_icon'] : '';
    $app_store_icon = isset($value) && isset($value['app_store_icon']) ? $value['app_store_icon'] : '';
    $site_intro = isset($value) && isset($value['site_intro']) ? $value['site_intro'] : '';
    $app_download_title = isset($value) && isset($value['app_download_title']) ? $value['app_download_title'] : '';
@endphp
<form action="#" class=" widget_input_field_form px-3 py-3 bg-white"
    onsubmit="event.preventDefault(); widgetInputFormSubmit(this);">
    {{-- Translated Language --}}
    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill border-light border-0">
                @php
                    $languages = getAllLanguages();
                @endphp
                @foreach ($languages as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link @if ($language->code == $lang) active border-0 @else bg-light @endif py-2"
                            href="javascript:void(0)"
                            onclick="getSidebarWidgetTranslationField(this,{{ $sidebar_has_widget_id }},{{ $widget_id }},'{{ $language->code }}')">
                            <img src="{{ asset('/public/web-assets/backend/img/flags/') . '/' . $language->code . '.png' }}"
                                width="20px" title="{{ $language->name }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <input type="hidden" name="lang" value="{{ $lang }}">
    {{-- Translated Language --}}
    <div class="form-group">
        <label for="site_intro">{{ translate('Site Intro') }}</label>
        <textarea id="site_intro" name="site_intro" class="theme-input-style style--two"
            placeholder="{{ translate('Site Intro') }}">{{ $site_intro }}</textarea>
    </div>

    <div class="form-group">
        <label for="app_download_title" class="">{{ translate('App Download Title') }}</label>
        <input type="text" class="form-control" id="app_download_title" name="app_download_title"
            placeholder="{{ translate('Enter Title') }}" value="{{ $app_download_title }}">
    </div>

    <!--Play Store-->
    <div class="form-group">
        <label for="play_store_link" class="">{{ translate('Play Store Link') }}</label>
        <input type="text" class="form-control" id="play_store_link" name="play_store_link"
            placeholder="{{ translate('Enter Link') }}" value="{{ $play_store_link }}"
            @if (!empty($lang) && $lang != getdefaultlang()) disabled @endif>
    </div>
    <div class="form-group">
        <label for="play_store_icon">{{ translate('Play Store Icon') }}</label>
        @include('core::base.includes.media.media_input', [
            'input' => 'play_store_icon',
            'data' => $play_store_icon,
        ])
    </div>


    <!--App Store-->
    <div class="form-group">
        <label for="app_store_link" class="">{{ translate('App App Store Link') }}</label>
        <input type="text" class="form-control" id="app_store_link" name="app_store_link"
            placeholder="{{ translate('Enter Link') }}" value="{{ $app_store_link }}"
            @if (!empty($lang) && $lang != getdefaultlang()) disabled @endif>
    </div>
    <div class="form-group">
        <label for="app_store_icon">{{ translate('App Store Icon') }}</label>
        @include('core::base.includes.media.media_input', [
            'input' => 'app_store_icon',
            'data' => $app_store_icon,
        ])
    </div>



    <div class="px-3 row justify-content-between">
        <div>
            <a href="javascript:;void(0)" class="text-danger"
                onclick="removeFromSidebar(this)">{{ translate('Delete') }}</a>
            <span class="mx-1">|</span>
            <a href="javascript:;void(0)" class="text-info"
                onclick="closeSidebarDropMenu(this)">{{ translate('Done') }}</a>
        </div>
        <button type="submit" class="btn btn-primary sm">{{ translate('Save') }}</button>
    </div>
</form>
