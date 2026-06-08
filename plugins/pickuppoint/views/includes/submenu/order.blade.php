@php
    $isactivatePickupPoint = isActivePlugin('pickuppoint');
@endphp
@if ($isactivatePickupPoint)
    @if (auth()->user()->can('Manage Pickup Point Order'))
        <li class="{{ Request::routeIs(['plugin.pickuppoint.orders']) ? 'active ' : '' }}">
            <a href="{{ route('plugin.pickuppoint.orders') }}">{{ translate('Pickup Point Order') }}<span
                    class="badge badge-danger ml-2">{{ translate('Free Addon') }}</span>
        </li></a>
    @endif
@endif
