<?php

namespace Plugin\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class UnitTranslation extends Model
{

    protected $table = "tl_com_unit_translations";

    protected $fillable = ['unit_id', 'lang'];
}
