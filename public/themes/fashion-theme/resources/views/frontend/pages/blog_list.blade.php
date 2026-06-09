@extends('theme/fashion-theme::frontend.layouts.master')
@section('seo')
    <title>{{ translate('Blogs', session()->get('api_locale')) }}</title>
    <meta name="title" content="{{ translate('Blogs', session()->get('api_locale')) }}" />
    <meta name="description" content="{{ get_setting('site_meta_description') }}" />
    <meta name="keywords" content="{{ get_setting('site_meta_keywords') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ translate('Blogs', session()->get('api_locale')) }}" />
    <meta property="og:description" content="{{ get_setting('site_meta_description') }}" />
    <meta name="twitter:card" content="{{ get_setting('site_meta_description') }}" />
    <meta name="twitter:title" content="{{ translate('Blogs', session()->get('api_locale')) }}" />
    <meta name="twitter:description" content="{{ get_setting('site_meta_description') }}" />
    <meta name="twitter:image" content="{{ getFilePath(get_setting('site_meta_image'), false) }}" />
    <meta property="og:image" content="{{ getFilePath(get_setting('site_meta_image'), false) }}" />
@endsection
