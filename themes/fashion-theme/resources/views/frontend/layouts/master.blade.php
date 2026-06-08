@php
    use Plugin\Ecommerce\Repositories\SettingsRepository;
    use Core\Repositories\SettingsRepository as CoreSettingRepository;
    use Illuminate\Support\Facades\Cache;

    $siteProperties = Cache::rememberForever('site-properties', function () {
        return CoreSettingRepository::SiteProperties();
    });

    $site_name = str_replace('"', '', $siteProperties['site_title']);
    $site_name = str_replace("'", '', $site_name);

    $site_motto = $siteProperties['site_motto'] != null ? '|' . $siteProperties['site_motto'] : '';
    $site_title = $site_name . '' . $site_motto;

    $default_language = defaultLanguage();
    $default_currency = SettingsRepository::defaultCurrency();

    $active_theme = getActiveTheme();

    $body_typography = themeOptionToCss('body_typography', $active_theme->id);
    $paragraph_typography = themeOptionToCss('paragraph_typography', $active_theme->id);
    $heading_typography = themeOptionToCss('heading_typography', $active_theme->id);
    $menu_typography = themeOptionToCss('menu_typography', $active_theme->id);
    $button_typography = themeOptionToCss('button_typography', $active_theme->id);
    $custom_js_properties = getThemeOption('custom_js', $active_theme->id);

    $site_mood_setting = getThemeOption('dark_light_switcher', $active_theme->id);
    $site_default_mood =
        isset($site_mood_setting['site_default_screen_mood']) &&
        $site_mood_setting['site_default_screen_mood'] == 'dark'
            ? 'dark'
            : '';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="manifest" href="/manifest.json">
    @if (get_setting('favicon') != null)
        <link rel="shortcut icon" href="{{ asset(getFilePath(get_setting('favicon'))) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('/public/web-assets/backend/img/favicon.png') }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
    <meta property="og:image:width" content="1200" />
    <meta name="brand_name" content="{{ $site_name }}" />
    <link rel="canonical" href="{{ env('APP_URL') }}" />
    <meta property="og:url" content="{{ env('APP_URL') }}" />
    <meta name="twitter:domain" content="{{ env('APP_URL') }}" />
    <meta property="og:site_name" content="{{ $site_name }}" />
    <meta name="twitter:site" content="{{ $site_name }}" />
    <meta name="apple-mobile-web-app-title" content="{{ $site_title }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/themes/fashion-theme/public/blog/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/themes/fashion-theme/public/css/custom_app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/back_to_top.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/header_logo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/blog.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/sidebar_options.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/page_404.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/subscribe.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/footer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/social_icon.css') }}">
    <!-- Including all google fonts link -->
    @includeIf('theme/fashion-theme::frontend.blog.includes.custom.google-font-link', [
        'body_typography' => $body_typography,
        'paragraph_typography' => $paragraph_typography,
        'heading_typography' => $heading_typography,
        'menu_typography' => $menu_typography,
        'button_typography' => $button_typography,
    ])
    <!-- Including all dynamic css -->
    @includeIf('theme/fashion-theme::frontend.blog.includes.custom.tl-dynamic-css', [
        'body_typography' => $body_typography,
        'paragraph_typography' => $paragraph_typography,
        'heading_typography' => $heading_typography,
        'menu_typography' => $menu_typography,
        'button_typography' => $button_typography,
    ])
    <!-- Theme Option Css -->

    {{-- Include Builder Css If Page or Homepage is Build with Builder --}}
    @yield('builder-css-link')
    <!--Custom script-->
    @if ($custom_js_properties != null)
        {!! $custom_js_properties['header_custom_js_code'] !!}
    @endif
    <!--End custom script-->
</head>

<body class="antialiased">
    <div id="app">
    </div>
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/fashion-theme/public/css/custom_css.css') }}">
    <script>
        //set site title
        let site_title = localStorage.getItem('site_title');
        localStorage.setItem('site_title', '<?php echo $site_title; ?>');

        //set default language
        let locale = localStorage.getItem('locale');
        if (locale == null) {
            localStorage.setItem('locale', '<?php echo $default_language; ?>');
        }
        //set selected currency
        let currency = localStorage.getItem('currency');

        if (currency == null) {
            localStorage.setItem('currency', '<?php echo $default_currency; ?>');
        }
        //set default currency
        localStorage.setItem('default_currency', '<?php echo $default_currency; ?>');

        //set default mood
        localStorage.setItem('mode', '<?php echo $site_default_mood; ?>');
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {})
                    .catch(error => {});
            });
        }
    </script>
    <!--Custom script-->
    @if ($custom_js_properties != null)
        {!! $custom_js_properties['footer_custom_js_code'] !!}
    @endif
    <!--End custom script-->

    @if (isActivePlugin('ecommerce'))
        <script src="{{ asset('/themes/fashion-theme/public/js/main.js?v=120') }}"></script>
    @endif

</body>

</html>
