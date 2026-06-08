@extends('core::base.layouts.master')
@section('title')
    {{ translate('Media Settings') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="theme-option-container">
        @include('core::base.business.includes.header', ['sub_link' => 'Media Settings'])
        <div class="theme-option-tab-wrap">
            @include('core::base.business.includes.tabs')
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <div class="card-header bg-white border-bottom2 py-3">
                        <h4>{{ translate('Media Settings') }}</h4>
                    </div>
                    <div class="form-wrapper p-3">
                        <form action="{{ route('core.store.media.settings') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('File Storage') }} </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="file_storage" class="theme-input-style file_storage_option">
                                        <option value="public" @selected(get_setting('file_storage') == 'public')>
                                            {{ translate('Local') }}
                                        </option>
                                        <option value="amazons3" @selected(get_setting('file_storage') == 'amazons3')>
                                            {{ translate('Amazon s3') }}
                                        </option>

                                    </select>
                                    @if ($errors->has('file_storage'))
                                        <div class="invalid-input">{{ $errors->first('file_storage') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="amazon-s3-setup {{ get_setting('file_storage') != 'amazons3' ? 'd-none' : '' }}">
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS ACCESS KEY ID') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="aws_access_key_id" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_ACCESS_KEY_ID') }}">
                                        @if ($errors->has('aws_access_key_id'))
                                            <div class="invalid-input">{{ $errors->first('aws_access_key_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS SECRET ACCESS KEY') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="aws_secret_access_key" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_SECRET_ACCESS_KEY') }}">
                                        @if ($errors->has('aws_secret_access_key'))
                                            <div class="invalid-input">{{ $errors->first('aws_secret_access_key') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS DEFAULT REGION') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="aws_default_region" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_DEFAULT_REGION') }}">
                                        @if ($errors->has('aws_default_region'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_default_region') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS BUCKET') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="aws_bucket" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}" value="{{ env('AWS_BUCKET') }}">
                                        @if ($errors->has('aws_bucket'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_bucket') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS URL') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="aws_endpoint" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}" value="{{ env('AWS_URL') }}">
                                        @if ($errors->has('aws_endpoint'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_endpoint') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Placeholder Image') }}</label>
                                </div>
                                <div class="col-md-8">
                                    @include('core::base.includes.media.media_input', [
                                        'input' => 'placeholder_image',
                                        'data' => get_setting('placeholder_image'),
                                    ])
                                    @if ($errors->has('placeholder_image'))
                                        <div class="invalid-input">{{ $errors->first('placeholder_image') }}</div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <h4 class="mb-4">{{ translate('Watermark Settings') }}</h4>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Enable/Disable Watermark') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <label class="switch glow primary medium">
                                        <input type="checkbox" name="watermark_status" id="watermark_status"
                                            onchange="toggleWatermarkSettings()" @checked(get_setting('watermark_status') == 'on')>
                                        <span class="control"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-row mb-20 watermark_image_settings">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Watermark Image') }}</label>
                                </div>
                                <div class="col-md-8">
                                    @include('core::base.includes.media.media_input', [
                                        'input' => 'watermark_image',
                                        'data' => get_setting('watermark_image'),
                                    ])
                                    @if ($errors->has('watermark_image'))
                                        <div class="invalid-input">{{ $errors->first('watermark_image') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20 watermark_image_settings">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Watermark Image Position') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="theme-input-style" name="watermark_image_position">
                                        <option value="top-left" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'top-left' ? 'selected' : '' }}>
                                            {{ translate('Top Left') }}
                                        </option>
                                        <option value="top" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'top' ? 'selected' : '' }}>
                                            {{ translate('Top') }}
                                        </option>
                                        <option value="top-right" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'top-right' ? 'selected' : '' }}>
                                            {{ translate('Top Right') }}
                                        </option>
                                        <option value="left" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'left' ? 'selected' : '' }}>
                                            {{ translate('Left') }}
                                        </option>
                                        <option value="center" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'center' ? 'selected' : '' }}>
                                            {{ translate('Center') }}
                                        </option>
                                        <option value="right" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'right' ? 'selected' : '' }}>
                                            {{ translate('Right') }}
                                        </option>
                                        <option value="bottom-left" class="text-uppercase"
                                            {{ get_setting('watermark_image_position') == 'bottom-left' ? 'selected' : '' }}>
                                            {{ translate('Bottom Left') }}
                                        </option>
                                    </select>
                                    @if ($errors->has('watermark_image'))
                                        <div class="invalid-input">{{ $errors->first('watermark_image') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-row mb-20 watermark_image_settings">
                                <div class="col-md-4">
                                    <label
                                        class="font-14 bold black">{{ translate('Watermarking image opacity (%)') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" name="water_marking_image_opacity" min="1"
                                        class="theme-input-style"
                                        value="{{ get_setting('water_marking_image_opacity') }}"
                                        placeholder="{{ translate('Watermarking image opacity') }}">
                                    @if ($errors->has('water_marking_image_opacity'))
                                        <div class="invalid-input">{{ $errors->first('water_marking_image_opacity') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <small class="text-danger">
                                **** {{ translate('Water mark is not applicable for pdf, zip, mp4 and webp media type.') }}
                            </small>
                            <div class="form-row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn long">{{ translate('Save Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script src="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                if (!$('#watermark_status').is(":checked")) {
                    $('.watermark_image_settings').hide()
                } else {
                    $('#selectImageApplicableFolder').select2({
                        theme: "classic",
                        placeholder: "{{ translate('Select image applicable folder') }}"
                    });
                }
                if (!$('#chunk_size_upload').is(":checked")) {
                    $('#chunk_size_upload_settings').hide()
                }
            });
            //Select file storage system
            $('.file_storage_option').on('change', function(e) {
                let value = $(this).val();
                if (value == 'amazons3') {
                    $('.amazon-s3-setup').removeClass('d-none');
                } else {
                    $('.amazon-s3-setup').addClass('d-none');
                }
            });

        })(jQuery);

        /**
         * Hide & show watermark settings 
         */
        function toggleWatermarkSettings() {
            "use strict";
            if (!$('#watermark_status').is(":checked")) {
                $('.watermark_image_settings').hide()
            } else {
                $('.watermark_image_settings').show()
                $('#selectImageApplicableFolder').select2({
                    theme: "classic",
                    placeholder: "{{ translate('Select image applicable folder') }}"
                });
            }
        }

        /**
         * Hide & show chunk size upload status 
         */
        function toggleChunkSizeUploadStatus() {
            "use strict";
            if (!$('#chunk_size_upload').is(":checked")) {
                $('#chunk_size_upload_settings').hide()
            } else {
                $('#chunk_size_upload_settings').show()
            }
        }
    </script>
@endsection
