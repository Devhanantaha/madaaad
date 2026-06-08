@extends('core::base.layouts.master')
@section('title')
    {{ translate('Payment Methods') }}
@endsection
@section('custom_css')
@section('custom_css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.css') }}">
    <!--  End select2  -->
    <!--Editor-->
    <link href="{{ asset('/public/web-assets/backend/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
    <!--End editor-->
    <style>
        .select2 {
            width: 100% !important;
        }

        .configuration {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .overlay {
            height: 100%;
            z-index: 9999;
            cursor: not-allowed;
        }
    </style>
@endsection
@endsection
@section('main_content')
<div class="border-bottom2 pb-3 mb-4">
    <h4><i class="icofont-pay"></i> {{ translate('Payment Methods') }}</h4>
</div>
<div class="row">
    <div class="col-lg-9 mx-auto payment-list">
        @if (count($payment_methods) > 0)
            @foreach ($payment_methods as $key => $method)
                <div class="card mb-30">
                    <div class="card-bod">
                        <div class="payment-method-items">
                            <div class="payment-method-item">
                                <!--Payment title-->
                                <div class="payment-method-item-header px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="payment-icon ">
                                                <i class="icofont-pay"></i>
                                            </div>
                                        </div>
                                        <div class="payment-logo">
                                            <h4 class="black">{{ $method->name }}</h4>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-15">
                                        <a href="#" data-toggle="collapse"
                                            data-target="item-body-{{ $method->id }}">
                                            <i class="icofont-ui-edit"></i> Edit
                                        </a>
                                        <label class="switch glow primary medium">
                                            <input type="checkbox" data-payment="{{ $method->id }}"
                                                class="payment-method-status"
                                                @if ($method->status == config('settings.general_status.active')) checked @endif />
                                            <span class="control"></span>
                                        </label>
                                    </div>
                                </div>
                                <!--End payment title-->
                                <!--Payment Configuration-->
                                <div id="item-body-{{ $method->id }}" class="hidden">
                                    @php
                                        $configuration_path =
                                            'plugin/ecommerce::payments.gateways.' .
                                            str_replace(' ', '', Str::lower($method->name)) .
                                            '.configuration';
                                    @endphp
                                    @includeIf($configuration_path, ['method' => $method])
                                </div>
                                <!--End payment Configuration-->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="alert alert-danger">{{ translate('No payment method found') }}</p>
        @endif
    </div>
</div>
@include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
<!--Select2-->
<script src="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.js') }}"></script>
<!--End Select2-->
<!--Editor-->
<script src="{{ asset('/public/web-assets/backend/plugins/summernote/summernote-lite.js') }}"></script>
<!--End Editor-->
<script>
    (function($) {
        "use strict";
        initDropzone()
        $(document).ready(function() {
            is_for_browse_file = true
            $('.selectCurrency').select2({
                theme: "classic",
                closeOnSelect: true
            })
            $('#bank_instruction').summernote({
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
                    ["insert", ["link"]],
                    ["view", ["fullscreen", "help"]],
                ],
                placeholder: 'Write your instruction here',
            });
            filtermedia()
        });
        /**
         *Active and deactivate product review
         *  
         **/
        $('.payment-method-status').on('change', function(e) {
            e.preventDefault();
            $(".payment-list").addClass("overlay");
            let id = $(this).data('payment');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: {
                    id: id
                },
                url: '{{ route('plugin.ecommerce.payments.methods.status.update') }}',
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    location.reload();
                }
            });
        });
        /**
         * Update payment method credential
         * 
         **/
        $('.payment-credential-update-btn').on('click', function(e) {
            e.preventDefault();
            let payment_id = $(this).data('payment-btn');
            $(document).find(".invalid-input").remove();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                data: $("#credential-form-" + payment_id).serialize(),
                url: '{{ route('plugin.ecommerce.payments.methods.credential.update') }}',
                success: function(response) {
                    if (response.success) {
                        toastr.success('{{ translate('Credential updated successfully') }}');
                    } else {
                        toastr.error('{{ translate('Update Failed ') }}');
                    }
                },
                error: function(response) {
                    if (response.status === 422) {
                        $.each(response.responseJSON.errors, function(field_name, error) {
                            $(document).find('[name=' + field_name + ']').closest(
                                '.input-option').after(
                                '<div class="invalid-input">' + error + '</div>')
                        })
                    } else {
                        toastr.error('{{ translate('Update Failed ') }}');
                    }
                }
            });
        });
    })(jQuery);
</script>
@endsection
