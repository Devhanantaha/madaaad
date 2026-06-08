@extends('core::base.layouts.master')
@section('title')
    {{ translate('New Banner') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-30">
                <div class="card-header bg-white border-bottom2 py-3">
                    <h4 class="mb-1">{{ translate('New Banner') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('plugin.ecommerce.app.configuration.banner.store') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Title') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="title" class="theme-input-style" value="{{ old('title') }}"
                                    placeholder="{{ translate('Type Title') }}">
                                @if ($errors->has('title'))
                                    <div class="invalid-input">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Image') }}</label>
                            </div>
                            <div class="col-sm-8">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'image',
                                    'data' => old('image'),
                                ])
                                @if ($errors->has('image'))
                                    <div class="invalid-input">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Resource type') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="resource_type" class="theme-input-style resource_type">
                                    @foreach (config('ecommerce.app_banner_type') as $key => $value)
                                        <option value="{{ $key }}" @selected(old('resource_type') == $key)>
                                            {{ ucwords(str_replace('_', ' ', $value)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('title'))
                                    <div class="invalid-input">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20 product option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Product') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="product" class="theme-input-style product-select">

                                </select>
                                @if ($errors->has('product'))
                                    <div class="invalid-input">{{ $errors->first('product') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20 d-none category option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Category') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="category" class="theme-input-style category-select">

                                </select>
                                @if ($errors->has('category'))
                                    <div class="invalid-input">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20 d-none brand option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Brand') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="brand" class="theme-input-style brand-select">

                                </select>
                                @if ($errors->has('brand'))
                                    <div class="invalid-input">{{ $errors->first('brand') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20 d-none flash_deal option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Deal') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="flash_deal" class="theme-input-style flash-deal-select">

                                </select>
                                @if ($errors->has('flash_deal'))
                                    <div class="invalid-input">{{ $errors->first('flash_deal') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20 d-none collection option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Collection') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="collection" class="theme-input-style collection-select">

                                </select>
                                @if ($errors->has('collection'))
                                    <div class="invalid-input">{{ $errors->first('collection') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="form-row mb-20 d-none custom_url option">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('URL') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="custom_url" class="theme-input-style"
                                    value="{{ old('url') }}" placeholder="{{ translate('Type URL') }}">
                                @if ($errors->has('url'))
                                    <div class="invalid-input">{{ $errors->first('url') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <button type="submit" class="btn long">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </form>
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
            initDropzone();
            $(document).ready(function() {
                $('.resource_type').on('change', function() {
                    let value = $(this).val();
                    $('.option').not('d-none').addClass('d-none');
                    $('.' + value).removeClass('d-none');
                });
            });

            /**
             *  product select
             * 
             */
            $('.product-select').select2({
                theme: "classic",
                placeholder: '{{ translate('Select product') }}',
                closeOnSelect: false,
                width: '100%',
                ajax: {
                    url: '{{ route('plugin.ecommerce.product.option') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            // Category
            $('.category-select').select2({
                theme: "classic",
                placeholder: '{{ translate('Select Category') }}',
                closeOnSelect: false,
                width: '100%',
                ajax: {
                    url: '{{ route('plugin.ecommerce.product.category.option') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });

            // Brand
            $('.brand-select').select2({
                theme: "classic",
                placeholder: '{{ translate('Select Brand') }}',
                closeOnSelect: false,
                width: '100%',
                ajax: {
                    url: '{{ route('plugin.ecommerce.product.brand.option') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            //deal
            $('.flash-deal-select').select2({
                theme: "classic",
                placeholder: '{{ translate('Select Deal') }}',
                width: '100%',
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('plugin.ecommerce.deal.option') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            //collection
            $('.collection-select').select2({
                theme: "classic",
                placeholder: '{{ translate('Select Collection') }}',
                width: '100%',
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('plugin.ecommerce.collection.option') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
        })(jQuery);
    </script>
@endsection
