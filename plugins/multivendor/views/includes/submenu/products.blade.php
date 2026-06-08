@if (auth()->user()->can('Manage Seller Products'))
    <li class="{{ Request::routeIs(['plugin.multivendor.admin.seller.products.list']) ? 'active ' : '' }}">
        <a href="{{ route('plugin.multivendor.admin.seller.products.list') }}">{{ translate('Seller Products') }}
            <span class="badge badge-danger ml-2">{{ translate('Free Addon') }}</span>
        </a>
    </li>
@endif
