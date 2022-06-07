<?php
/**
 * File name: Market.php
 * Last modified: 2020.06.07 at 07:02:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Market
 * @package App\Models
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection Product
 * @property \Illuminate\Database\Eloquent\Collection Gallery
 * @property \Illuminate\Database\Eloquent\Collection MarketsReview
 * @property \Illuminate\Database\Eloquent\Collection[] discountables
 * @property \Illuminate\Database\Eloquent\Collection[] fields
 * @property \Illuminate\Database\Eloquent\Collection[] User
 * @property \Illuminate\Database\Eloquent\Collection[] Market
 * @property string name
 * @property string description
 * @property string address
 * @property string latitude
 * @property string longitude
 * @property string phone
 * @property string mobile
 * @property string information
 * @property double admin_commission
 * @property double delivery_fee
 * @property double default_tax
 * @property double delivery_range
 * @property boolean available_for_delivery
 * @property boolean closed
 * @property boolean active
 */
class Partner extends Model
{
    public $table = 'partners';
    public $fillable = [
        'business_name',
        'business_address',
        'business_type_id',
        'full_name',
        'position',
        'email',
        'phone_number',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'business_name' => 'string',
        'business_address' => 'string',
        'business_type_id' => 'string',
        'full_name' => 'string',
        'position' => 'string',
        'email' => 'string',
        'phone_number' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $adminRules = [
        'business_name' => 'required',
        'business_address' => 'required',
        'business_type_id' => 'required',
        'full_name' => 'required',
        'position' => 'required',
        'email' => 'required',
        'phone_number' => 'required',
    ];

    public function businessType()
    {
        return $this->morphMany('App\Models\BusinessTypes', 'businessable');
    }

}
