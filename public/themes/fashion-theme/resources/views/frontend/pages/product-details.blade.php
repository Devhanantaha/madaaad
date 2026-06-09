@extends('theme/fashion-theme::frontend.layouts.master')
@section('seo')
    @if ($product_details != null)
        <title> {{ $product_details->translation('name', session()->get('api_locale')) }}</title>
        <meta name="title"
            content="{{ $product_details->meta_title != null ? $product_details->meta_title : $product_details->name }}" />
        <meta name="description"
            content="{{ $product_details->meta_description != null ? $product_details->meta_description : $product_details->name }}" />
        <meta name="keywords" content="{{ get_setting('site_meta_keywords') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title"
            content="{{ $product_details->meta_title != null ? $product_details->meta_title : $product_details->name }}" />
        <meta property="og:description"
            content="{{ $product_details->meta_description != null ? $product_details->meta_description : $product_details->name }}" />
        <meta name="twitter:card"
            content="{{ $product_details->meta_title != null ? $product_details->meta_title : $product_details->name }}" />
        <meta name="twitter:title"
            content="{{ $product_details->meta_title != null ? $product_details->meta_title : $product_details->name }}" />
        <meta name="twitter:description"
            content="{{ $product_details->meta_description != null ? $product_details->meta_description : $product_details->name }}" />
        <meta name="twitter:image" content="{{ $product_details->meta_image }}" />
        <meta property="og:image" content="{{ $product_details->meta_image }}" />
        <meta property="og:image:width" content="1200" />
    @else
        <title>{{ get_setting('site_title') }}</title>
        <meta name="title" content="{{ get_setting('site_meta_title') }}" />
        <meta name="description" content="{{ get_setting('site_meta_description') }}" />
        <meta name="keywords" content="{{ get_setting('site_meta_keywords') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{ get_setting('site_meta_title') }}" />
        <meta property="og:description" content="{{ get_setting('site_meta_description') }}" />
        <meta name="twitter:card" content="{{ get_setting('site_meta_description') }}" />
        <meta name="twitter:title" content="{{ get_setting('site_meta_title') }}" />
        <meta name="twitter:description" content="{{ get_setting('site_meta_description') }}" />
        <meta name="twitter:image" content="{{ getFilePath(get_setting('site_meta_image'), false) }}" />
        <meta property="og:image" content="{{ getFilePath(get_setting('site_meta_image'), false) }}" />
    @endif
@endsection
