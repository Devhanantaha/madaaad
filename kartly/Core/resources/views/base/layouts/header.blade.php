<header class="header white-bg d-flex align-content-center flex-wrap">

    <!-- Main Header -->
    <div class="main-header">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-3 col-lg-5 col-xl-4">
                    <!-- Header Left -->
                    <div class="main-header-left h-100 d-flex align-items-center gap-15">
                        <div class="main-header-menu d-none d-lg-block">
                            <div class="sidebar-toggle-button header-toogle-menu-desktop">
                                <img src="{{ asset('/public/web-assets/backend/img/menu.png') }}" alt="">
                            </div>
                        </div>
                        <!-- Main Header Menu -->
                        <div class="main-header-menu d-block d-lg-none">
                            <div class="header-toogle-menu">
                                <img src="{{ asset('/public/web-assets/backend/img/menu.png') }}" alt="">
                            </div>
                        </div>
                        <!-- End Main Header Menu -->
                        <!-- Main Header Button -->
                        <div class="main-header-btn d-none d-lg-flex">
                            <a href="{{ route('core.admin.clear.system.cache') }}"
                                class="btn btn-info btn-medium">{{ translate('Clear Cache') }}</a>
                        </div>
                        <!-- End Main Header Button -->
                        <!-- Visit website -->
                        <div class="d-none d-lg-block">
                            <a href="/" target="_blank" title="Visit website" class="btn btn-medium">
                                Visit website
                            </a>
                        </div>
                        <!-- End Visit website -->

                    </div>
                    <!-- End Header Left -->
                </div>
                <div class="col-9 col-lg-7 col-xl-8">
                    <!-- Header Right -->
                    <div class="main-header-right d-flex justify-content-end gap-15 align-items-center">
                        <!-- Main Header Time -->
                        <div class="main-header-date-time text-right d-none d-lg-block"
                            data-timezone="{{ get_setting('default_timezone') }}" id="dateTime">
                            <h3 class="time">
                                <span id="hours">21</span>
                                <span id="point">:</span>
                                <span id="min">06</span>
                            </h3>
                            <span class="date"><span id="date">Tue, 12 October
                                    2019</span></span>
                        </div>
                        <!-- End Main Header Time -->
                        <div class="main-header-notification">
                            <a href="#" class="header-icon notification-icon" data-toggle="dropdown"
                                title="Language Options">
                                @if (isset($active_lang->code))
                                    <img src="{{ asset('/public/web-assets/backend/img/flags/') . '/' . $active_lang->code . '.png' }}"
                                        class="w-20" alt="{{ $active_lang->code }}">
                                @endif
                            </a>
                            <div id="lang-change" class="dropdown-menu style--three">
                                @foreach ($active_langs as $lang)
                                    <a href="#" class="dropdown-item" data-lan="{{ $lang->code }}">
                                        <img src="{{ asset('/public/web-assets/backend/img/flags/') . '/' . $lang->code . '.png' }}"
                                            class="mr-2 w-20" alt="{{ $lang->code }}">
                                        {{ $lang->native_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <!-- Main Header Notification -->
                        <div class="main-header-notification">
                            <a href="#" class="header-icon notification-icon" data-toggle="dropdown"
                                title="Notification">
                                <span class="count notification-counter"
                                    data-bg-img="{{ asset('/public/web-assets/backend/img/count-bg.png') }}">0</span>
                                <i class="icofont-notification fz-22"></i>
                            </a>
                            <div class="dropdown-menu style--two dropdown-menu-right py-0">
                                <div
                                    class="dropdown-header bg-primary-light d-flex align-items-center justify-content-between">
                                    <h4 class="py-2 font-weight-normal">{{ translate('Notifications') }}</h4>
                                    <a href="#"
                                        class="text-mute mark-as-all-read d-none">{{ translate('Clear all') }}</a>
                                </div>
                                <div class="dropdown-body notification-list-items">
                                </div>
                            </div>
                        </div>
                        <!-- End Main Header Notification -->
                        <!-- Main Header User -->
                        @auth
                            <div class="main-header-user position-relative">
                                <a href="#" class="d-flex align-items-center gap-10" data-toggle="dropdown">
                                    <div class="profile-info">
                                        <span class="name text-dark">{{ auth()->user()->name }}</span>
                                        <span
                                            class="role">{{ auth()->user()->getRoleNames()->implode(', ') ?? 'No role' }}</span>

                                    </div>
                                    <div class="header-icon">
                                        @if (auth()->user()->image != null)
                                            <img src="{{ asset(getFilePath(auth()->user()->image, true)) }}"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle">
                                        @else
                                            <img src="{{ asset('/public/web-assets/backend/img/png-icon/user.png') }}"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle">
                                        @endif
                                    </div>
                                </a>
                                <div class="dropdown-menu profile-dropdown">
                                    <div class="d-flex justify-content-start p-2">
                                        @if (auth()->user()->image != null)
                                            <img src="{{ asset(getFilePath(auth()->user()->image, true)) }}"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle img-50">
                                        @else
                                            <img src="{{ asset('/public/web-assets/backend/img/png-icon/user.png') }}"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle img-50">
                                        @endif
                                        <div class="ms-1">
                                            <span class="name pb-1">{{ auth()->user()->name }}</span>
                                            <p class="pb-2 text-muted">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="dropdown-divider">
                                    <a href="{{ route('core.profile') }}" class="dropdown-item">
                                        <i class="icofont-settings"></i> {{ translate('Settings') }}
                                    </a>
                                    <hr class="dropdown-divider">
                                    <a href="{{ route('core.logout') }}" class="dropdown-item">
                                        <i class="icofont-sign-out"></i> {{ translate('Log Out') }}
                                    </a>
                                </div>
                            </div>
                        @endauth
                        <!-- End Main Header User -->
                    </div>
                    <!-- End Header Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Header -->
</header>
