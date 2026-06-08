<?php

namespace Plugin\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Plugin\Ecommerce\Models\Cities;
use Plugin\Ecommerce\Models\States;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Plugin\Ecommerce\Models\LocationWiseShippingRateCity;
use Plugin\Ecommerce\Models\LocationWiseShippingRateStates;

class LocationWiseShippingRate extends Model
{
    protected $table = "tl_com_location_wise_shipping_rates";

    public function countries(): HasManyThrough
    {
        return $this->hasManyThrough(Country::class, LocationWiseShippingRateCountry::class, 'rate_id', 'id', 'id', 'country_id');
    }

    public function states(): HasManyThrough
    {
        return $this->hasManyThrough(States::class, LocationWiseShippingRateStates::class, 'rate_id', 'id', 'id', 'state_id');
    }

    public function cities(): HasManyThrough
    {
        return $this->hasManyThrough(Cities::class, LocationWiseShippingRateCity::class, 'rate_id', 'id', 'id', 'city_id');
    }
}
