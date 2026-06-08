@if (auth()->user()->shop != null)
    <div class="logo justify-content-center align-items-center d-flex py-1">
        @if (auth()->user()->shop->logo != null)
            <img src="{{ asset(getFilePath(auth()->user()->shop->logo, true)) }}"
                alt="{{ auth()->user()->shop->shop_name }}" class="img-fluid">
        @else
            <h3 class="default-logo text-white">{{ auth()->user()->shop->shop_name }}</h3>
        @endif
    </div>
@else
    <div class="logo pl-20 align-items-center d-flex py-3">
        @if (get_setting('admin_dark_logo') != null)
            <a href="{{ route('plugin.multivendor.seller.dashboard') }}" class="default-logo">
                <img src="{{ asset(getFilePath(get_setting('admin_dark_logo'))) }}">
            </a>
        @else
            <h3 class="default-logo text-white">{{ get_setting('system_name') }}</h3>
        @endif
    </div>
@endif
