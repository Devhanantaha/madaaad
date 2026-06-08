@php
    if (!empty($data)) {
        $file_details = getFileDetails($data);
        if ($file_details != null) {
            $image_src = getFilePath($file_details->id);
        } else {
            $image_src = getPlaceHolderImagePath();
        }
    } else {
        $image_src = getPlaceHolderImagePath();
    }

    $user_filter = isset($user_filter) && $user_filter == true ? 'true' : 'false';
@endphp

<input type="hidden" name="{{ $input }}" id="{{ $input }}_id" value="{{ $data }}"
    @if (isset($disable) && $disable === true) disabled @endif>
<div class="image-box">
    <div class="d-flex flex-wrap gap-10 mb-3">
        @if (isset($data))
            <div class="preview-image-wrapper">
                <img src="{{ $image_src }}" alt="file-input" class="preview_image" id="{{ $input }}_preview" />

                <button type="button" title="Remove image" class="remove-btn style--three black 555"
                    id="{{ $input }}_remove"
                    onclick="removeSelection('#{{ $input }}_preview,#{{ $input }}_id,#{{ $input }}_remove')">
                    <i class="icofont-close"></i>
                </button>
            </div>
        @else
            <div class="preview-image-wrapper">
                <img src="{{ $image_src }}" class="preview_image" alt="file-input"
                    id="{{ $input }}_preview" />
                <button type="button" title="Remove image" class="remove-btn style--three black d-none"
                    id="{{ $input }}_remove"
                    onclick="removeSelection('#{{ $input }}_preview,#{{ $input }}_id,#{{ $input }}_remove')">
                    <i class="icofont-close"></i>
                </button>
            </div>
        @endif

    </div>
    <div class="image-box-actions">
        <button type="button" class="btn-link" data-toggle="modal" data-target="#mediaUploadModal"
            id="{{ $input }}_choose"
            onclick="setDataInsertableIds('#{{ $input }}_preview,#{{ $input }}_id,#{{ $input }}_remove', {{ $user_filter }})">
            {{ translate('Choose File') }}
        </button>
    </div>
</div>
