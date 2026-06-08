@php
    use Core\Views\Composer\Core;
    $shareable_data = new Core();
    $active_langs = $shareable_data->active_langs;
    $active_lang = $shareable_data->active_lang;
    $style_path = $shareable_data->style_path;
    $mood = $shareable_data->mood;
@endphp
@include('core::base.layouts.head')

<body>
    <div class="offcanvas-overlay"></div>
    <div class="wrapper">
        <div class="main-wrapper">
            @include('plugin/multivendor::seller.dashboard.layouts.side_bar')
            <div class="main-content">
                @include('plugin/multivendor::seller.dashboard.layouts.seller_header')
                <div class="container-fluid">
                    @include('core::base.layouts.dark_light_switcher')
                    @if (auth()->user()->status == config('settings.general_status.active'))
                        @yield('seller_main_content')
                    @else
                        <p class="alert alert-info">Your Account is Inactive. Please contact with Administration </p>
                    @endif
                </div>
            </div>
        </div>
        @include('core::base.layouts.footer')
    </div>
    @include('core::base.layouts.script')
</body>

</html>
