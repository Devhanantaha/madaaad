|<nav class="sidebar" data-trigger="scrollbar">
    @include('plugin/multivendor::seller.dashboard.layouts.headerLogo')
    <!-- Sidebar -->
    <div class="sidebar-body">
        <ul class="nav">
            <li class="{{ Request::routeIs('plugin.multivendor.seller.dashboard') ? 'active ' : '' }}">
                <a href="{{ route('plugin.multivendor.seller.dashboard') }}">
                    <i class="icofont-dashboard"></i>
                    <span class="link-title">{{ translate('Dashboard') }}</span>
                </a>
            </li>
            <li
                class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.products.list', 'plugin.multivendor.seller.dashboard.products.add', 'plugin.multivendor.seller.dashboard.products.edit']) ? 'active ' : '' }}">
                <a href="{{ route('plugin.multivendor.seller.dashboard.products.list') }}">
                    <i class="icofont-bucket1"></i>
                    <span class="link-title">{{ translate('Products') }}</span>
                </a>
            </li>
            <li class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.order.list']) ? 'active ' : '' }}">
                <a href="{{ route('plugin.multivendor.seller.dashboard.order.list') }}">
                    <i class="icofont-cart"></i>
                    <span class="link-title">{{ translate('Orders') }}</span>
                </a>
            </li>
            @if (isActivePlugin('refund'))
                <li
                    class="{{ Request::routeIs('plugin.multivendor.seller.dashboard.order.refund.list') ? 'active ' : '' }}">
                    <a href="{{ route('plugin.multivendor.seller.dashboard.order.refund.list') }}">
                        <i class="icofont-ui-previous"></i>
                        <span class="link-title">{{ translate('Refunds') }}</span>
                    </a>
                </li>
            @endif
            <li
                class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.earning.history', 'plugin.multivendor.seller.dashboard.earning.payouts', 'plugin.multivendor.seller.dashboard.earning.payout.settings', 'plugin.multivendor.seller.dashboard.earning.payout.requests']) ? 'active sub-menu-opened' : '' }}">
                <a href="#">
                    <i class="icofont-money"></i>
                    <span class="link-title">{{ translate('Earning') }}</span>
                </a>
                <ul class="nav sub-menu">
                    <li
                        class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.earning.payout.requests']) ? 'active ' : '' }}">
                        <a
                            href="{{ route('plugin.multivendor.seller.dashboard.earning.payout.requests') }}">{{ translate('Payouts Requests') }}</a>
                    </li>
                    <li
                        class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.earning.payouts']) ? 'active ' : '' }}">
                        <a
                            href="{{ route('plugin.multivendor.seller.dashboard.earning.payouts') }}">{{ translate('Payouts') }}</a>
                    </li>
                    <li
                        class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.earning.history']) ? 'active ' : '' }}">
                        <a
                            href="{{ route('plugin.multivendor.seller.dashboard.earning.history') }}">{{ translate('Earning History') }}</a>
                    </li>
                    <li
                        class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.earning.payout.settings']) ? 'active ' : '' }}">
                        <a
                            href="{{ route('plugin.multivendor.seller.dashboard.earning.payout.settings') }}">{{ translate('Payout Settings') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.reviews.list']) ? 'active ' : '' }}">
                <a href="{{ route('plugin.multivendor.seller.dashboard.reviews.list') }}">
                    <i class="icofont-ui-rating"></i>
                    <span class="link-title">{{ translate('Reviews') }}</span>
                </a>
            </li>
            <li class="{{ Request::routeIs(['plugin.multivendor.seller.dashboard.shop.settings']) ? 'active ' : '' }}">
                <a href="{{ route('plugin.multivendor.seller.dashboard.shop.settings') }}">
                    <i class="icofont-ui-settings"></i>
                    <span class="link-title">{{ translate('Shop Settings') }}</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- End Sidebar -->
</nav>
