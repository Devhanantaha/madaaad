@php
    use Core\Views\Composer\Core;
    $shareable_data = new Core();
    $active_langs = $shareable_data->active_langs;
    $active_lang = $shareable_data->active_lang;
    $style_path = $shareable_data->style_path;
    $mood = $shareable_data->mood;
    $placeholder_image = getFilePath(get_setting('placeholder_image'));
@endphp
@include('core::base.layouts.head')

<body>
    <div class="offcanvas-overlay"></div>
    <div class="wrapper">
        <div class="main-wrapper">
            @include('core::base.layouts.navbar')
            <div class="main-content">
                @include('core::base.layouts.header')
                <div class="container-fluid">

                    @if (isActivePlugin('multivendor'))
                        @php
                            $supper_admin_id = getSupperAdminId();
                            $shop = \Plugin\Multivendor\Models\SellerShop::where(
                                'seller_id',
                                $supper_admin_id,
                            )->first();
                        @endphp
                        @if ($shop == null)
                            <p class="alert alert-warning">
                                Please completed shop setup first, Otherwise product will not show in the frontend.
                                <a href="{{ route('plugin.ecommerce.ecommerce.configuration', ['tab' => 'shop']) }}">
                                    Click Here
                                </a>
                            </p>
                        @endif
                    @endif

                    @include('core::base.layouts.dark_light_switcher')
                    @yield('main_content')
                </div>
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Main Wrapper -->

        <!-- Footer -->
        @include('core::base.layouts.footer')
        <!-- End Footer -->
    </div>
    <!-- End wrapper -->
    @include('core::base.layouts.script')
</body>

</html>
