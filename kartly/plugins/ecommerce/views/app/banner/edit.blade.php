@extends('core::base.layouts.master')
@section('title')
    {{ translate('Edit Banner') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('/public/web-assets/backend/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-30">
                <div class="card-header bg-white border-bottom2 py-3">
                    <h4 class="mb-1">{{ translate('Edit Banner') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="alert alert-info">You are editing <strong>"{{ getLanguageNameByCode($lang) }}"</strong>
                            version
                        </p>
                    </div>
                    <ul class="nav nav-tabs nav-fill border-light border-0">
                        @foreach ($languages as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link @if ($language->code == $lang) active border-0 @else bg-light @endif py-3"
                                    href="{{ route('plugin.ecommerce.app.configuration.banner.edit', ['id' => $banner->id, 'lang' => $language->code]) }}">
                                    <img src="{{ asset('/public/web-assets/backend/img/flags/') . '/' . $language->code . '.png' }}"
                                        width="20px">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <hr>
                    <form action="{{ route('plugin.ecommerce.app.configuration.banner.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $banner->id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Title') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="title" class="theme-input-style"
                                    value="{{ $banner->translation('title', $lang) }}"
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
                                    'data' => $banner->translation('image', $lang),
                                ])
                                @if ($errors->has('image'))
                                    <div class="invalid-input">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-20 @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Resource type') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="resource_type" class="theme-input-style resource_type">
                                    @foreach (config('ecommerce.app_banner_type') as $key => $value)
                                        <option value="{{ $key }}" @selected($banner->type == $key)>
                                            {{ ucwords(str_replace('_', ' ', $value)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('title'))
                                    <div class="invalid-input">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div
                            class="form-row mb-20 d-none product option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Product') }} </label>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $product = \Plugin\Ecommerce\Models\Product::where('id', $banner->url)->first();
                                @endphp
                                <select name="product" class="theme-input-style product-select">
                                    @if ($product != null && $banner->type == 'product')
                                        <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                                    @endif
                                </select>
                                @if ($errors->has('product'))
                                    <div class="invalid-input">{{ $errors->first('product') }}</div>
                                @endif
                            </div>
                        </div>

                        <div
                            class="form-row mb-20 d-none category option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Category') }} </label>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $category = \Plugin\Ecommerce\Models\ProductCategory::where(
                                        'id',
                                        $banner->url,
                                    )->first();
                                @endphp
                                <select name="category" class="theme-input-style category-select">
                                    @if ($category != null && $banner->type == 'category')
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                    @endif
                                </select>
                                @if ($errors->has('category'))
                                    <div class="invalid-input">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>

                        <div
                            class="form-row mb-20 d-none brand option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Brand') }} </label>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $brand = \Plugin\Ecommerce\Models\ProductBrand::where('id', $banner->url)->first();
                                @endphp
                                <select name="brand" class="theme-input-style brand-select">
                                    @if ($brand != null && $banner->type == 'brand')
                                        <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                                    @endif
                                </select>
                                @if ($errors->has('brand'))
                                    <div class="invalid-input">{{ $errors->first('brand') }}</div>
                                @endif
                            </div>
                        </div>

                        @if (isActivePlugin('flashdeal'))
                            <div
                                class="form-row mb-20 d-none flash_deal option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('Select Deal') }} </label>
                                </div>
                                <div class="col-sm-8">
                                    @php
                                        $deal = \Plugin\Flashdeal\Models\FlashDeal::where('id', $banner->url)->first();
                                    @endphp
                                    <select name="flash_deal" class="theme-input-style flash-deal-select">
                                        @if ($deal != null && $banner->type == 'flash_deal')
                                            <option value="{{ $deal->id }}" selected>{{ $deal->title }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('flash_deal'))
                                        <div class="invalid-input">{{ $errors->first('flash_deal') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div
                            class="form-row mb-20 d-none collection option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Select Collection') }} </label>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $collection = \Plugin\Ecommerce\Models\ProductCollection::where(
                                        'id',
                                        $banner->url,
                                    )->first();
                                @endphp
                                <select name="collection" class="theme-input-style collection-select">
                                    @if ($collection != null && $banner->type == 'collection')
                                        <option value="{{ $collection->id }}" selected>{{ $collection->name }}</option>
                                    @endif
                                </select>
                                @if ($errors->has('collection'))
                                    <div class="invalid-input">{{ $errors->first('collection') }}</div>
                                @endif
                            </div>
                        </div>


                        <div
                            class="form-row mb-20 d-none custom_url option @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('URL') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="custom_url" class="theme-input-style"
                                    value="{{ $banner->url }}" placeholder="{{ translate('Type URL') }}">
                                @if ($errors->has('url'))
                                    <div class="invalid-input">{{ $errors->first('url') }}</div>
                                @endif
                            </div>
                        </div>



                        <div class="form-row mb-20 @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Status') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="status-select form-control attributes" name="status">
                                    <option value="{{ config('settings.general_status.active') }}"
                                        @selected($banner->status == config('settings.general_status.active'))>{{ translate('Active') }}</option>
                                    <option value="{{ config('settings.general_status.in_active') }}"
                                        @selected($banner->status == config('settings.general_status.in_active'))>{{ translate('Inactive') }}</option>
                                </select>
                                @if ($errors->has('status'))
                                    <div class="invalid-input">{{ $errors->first('status') }}</div>
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
                let currentValue = $('.resource_type').val();
                $('.' + currentValue).removeClass('d-none');

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
