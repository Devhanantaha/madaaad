@php
    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    $active_langs = getAllLanguages();
@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('General Settings') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.css') }}">
    <link href="{{ asset('/public/web-assets/backend/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
@endsection
@push('head')
    <style>
        .important-border {
            border: 1px solid #dddcdc !important
        }

        .form-row {
            align-items: center !important;
        }
    </style>
@endpush
@section('main_content')
    <div class="theme-option-container">
        @include('core::base.business.includes.header', ['sub_link' => 'General'])
        <div class="theme-option-tab-wrap">
            @include('core::base.business.includes.tabs')
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <div class="card">
                        <div class="card-header bg-white border-bottom2 py-3">
                            <h4>{{ translate('General Settings') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('core.store.general.settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card important-border mb-20">
                                    <div class="card-body">
                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label
                                                    class="font-14 bold black text-capitalize">{{ translate('Site Title') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="system_name" class="theme-input-style"
                                                    value="{{ get_setting('system_name') }}"
                                                    placeholder="{{ translate('Site Title') }}">
                                                @if ($errors->has('system_name'))
                                                    <div class="invalid-input">{{ $errors->first('system_name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label class="font-14 bold black">{{ translate('Site Motto') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="site_moto" class="theme-input-style"
                                                    value="{{ get_setting('site_moto') }}"
                                                    placeholder="{{ translate('Site Moto') }}">
                                                @if ($errors->has('site_moto'))
                                                    <div class="invalid-input">{{ $errors->first('site_moto') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label class="font-14 bold black">{{ translate('Favicon') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                @include('core::base.includes.media.media_input', [
                                                    'input' => 'favicon',
                                                    'data' => get_setting('favicon'),
                                                ])
                                                @if ($errors->has('favicon'))
                                                    <div class="invalid-input">{{ $errors->first('favicon') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Desktop Logo-->
                                <div class="card important-border mb-20">
                                    <div class="card-header">
                                        <h5>{{ translate('Desktop Logo') }}</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-row mb-20">
                                            <div class="col-lg-6">
                                                <h6>{{ translate('Light Mood Logo') }}</h6>
                                                <hr>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Light Logo') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'white_background_logo',
                                                            'data' => get_setting('white_background_logo'),
                                                        ])
                                                        @if ($errors->has('white_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('white_background_logo') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Sticky Logo') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'sticky_background_logo',
                                                            'data' => get_setting('sticky_background_logo'),
                                                        ])
                                                        @if ($errors->has('sticky_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('sticky_background_logo') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <h6>{{ translate('Dark Mood Logo') }}</h6>
                                                <hr>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Dark Logo') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'black_background_logo',
                                                            'data' => get_setting('black_background_logo'),
                                                        ])
                                                        @if ($errors->has('black_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('black_background_logo') }}</div>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Dark Sticky Logo') }}</label>
                                                    </div>

                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'sticky_black_background_logo',
                                                            'data' => get_setting('sticky_black_background_logo'),
                                                        ])
                                                        @if ($errors->has('sticky_black_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('sticky_black_background_logo') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--End Desktop Logo-->

                                <!--Mobile Logo-->
                                <div class="card important-border mb-20">
                                    <div class="card-header">
                                        <h5>{{ translate('Mobile Logo') }}</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-row mb-20">
                                            <div class="col-lg-6">
                                                <h6>{{ translate('Light Mood Logo') }}</h6>
                                                <hr>

                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Logo (Mobile)') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'white_mobile_background_logo',
                                                            'data' => get_setting('white_mobile_background_logo'),
                                                        ])
                                                        @if ($errors->has('white_mobile_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('white_mobile_background_logo') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Sticky Logo (Mobile)') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'sticky_mobile_background_logo',
                                                            'data' => get_setting('sticky_mobile_background_logo'),
                                                        ])
                                                        @if ($errors->has('sticky_mobile_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('sticky_mobile_background_logo') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <h6>{{ translate('Dark Mood Logo') }}</h6>
                                                <hr>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Dark Logo (Mobile)') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'black_mobile_background_logo',
                                                            'data' => get_setting('black_mobile_background_logo'),
                                                        ])
                                                        @if ($errors->has('black_mobile_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('black_mobile_background_logo') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Dark Sticky Logo (Mobile)') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'sticky_black_mobile_background_logo',
                                                            'data' => get_setting(
                                                                'sticky_black_mobile_background_logo'),
                                                        ])
                                                        @if ($errors->has('sticky_black_mobile_background_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('sticky_black_mobile_background_logo') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--End Mobile Logo-->

                                <!--Admin Panel Logo-->
                                <div class="card important-border mb-20">
                                    <div class="card-header">
                                        <h5>{{ translate('Admin Panel Logo') }}</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-row mb-20">
                                            <div class="col-lg-6">
                                                <div class="form-row mb-20">
                                                    <div class="col-md-4">
                                                        <label
                                                            class="font-14 bold black">{{ translate('Admin Logo') }}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        @include('core::base.includes.media.media_input', [
                                                            'input' => 'admin_dark_logo',
                                                            'data' => get_setting('admin_dark_logo'),
                                                        ])
                                                        @if ($errors->has('admin_dark_logo'))
                                                            <div class="invalid-input">
                                                                {{ $errors->first('admin_dark_logo') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!--End Admin Panel Logo-->


                                <div class="card important-border mb-20">
                                    <div class="card-body">
                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label
                                                    class="font-14 bold black">{{ translate('Default Language') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="default-language form-control" name="default_language"
                                                    id="default_language"
                                                    placeholder="{{ translate('Select default language') }}">
                                                    @foreach ($active_langs as $lang)
                                                        @if ($lang->status == config('settings.general_status.active'))
                                                            <option value="{{ $lang->id }}"
                                                                @selected(get_setting('default_language') == $lang->id)>
                                                                {{ $lang->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('default_language'))
                                                    <div class="invalid-input">{{ $errors->first('default_language') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label
                                                    class="font-14 bold black">{{ translate('Select Default Timezone') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="default-timezone form-control" name="default_timezone"
                                                    id="default_timezone"
                                                    placeholder="{{ translate('Select Default Timezone') }}">
                                                    @foreach ($tzlist as $tz)
                                                        <option value="{{ $tz }}" @selected(get_setting('default_timezone') == $tz)>
                                                            {{ $tz }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('default_timezone'))
                                                    <div class="invalid-input">{{ $errors->first('default_timezone') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-row mb-20">
                                            <div class="col-md-4">
                                                <label
                                                    class="font-14 bold black">{{ translate('Copyright Text') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="editor-wrap">
                                                    <textarea name="copyright_text" id="copyright_text">{{ get_setting('copyright_text') }}</textarea>
                                                </div>
                                                @if ($errors->has('copyright_text'))
                                                    <div class="invalid-input">{{ $errors->first('copyright_text') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card important-border mb-20">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-md-12 text-right">
                                                <button type="submit"
                                                    class="btn long">{{ translate('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script src="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.js') }}"></script>
    <!--Editor-->
    <script src="{{ asset('/public/web-assets/backend/plugins/summernote/summernote-lite.js') }}"></script>
    <!--End Editor-->
    <script type="application/javascript">
    (function($) {
        "use strict";
        initDropzone()
        $(document).ready(function() {
            is_for_browse_file = true
            filtermedia()
            /*Select default language*/
            $('.default-language').select2({
                theme: "classic",
            });
            /*Select default timezone*/
            $('.default-timezone').select2({
                theme: "classic",
            });
            /*Select default currency*/
            $('.default-currency').select2({
                theme: "classic",
            });
            /*Select currency position*/
            $('.currency-position').select2({
                theme: "classic",
            });

            $('#copyright_text').summernote({
                tabsize: 2,
                height: 200,
                codeviewIframeFilter: false,
                codeviewFilter: true,
                codeviewFilterRegex: /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "video"]],
                    ["view", ["fullscreen", "codeview","help"]],
                ],
                placeholder: 'Copyright text',
                callbacks: {
                    onChangeCodeview: function(contents, $editable) {
                        let code = $(this).summernote('code')
                        code = code.replace(
                            /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                            '')
                        $(this).val(code)
                    }
                }
            });
        })
    })(jQuery);
</script>
@endsection
