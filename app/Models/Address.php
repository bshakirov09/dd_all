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
class Address extends Model
{
    public $table = 'addresses';
    public $fillable = [
        'apartment',
        'street',
        'city',
        'state',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'apartment' => 'string',
        'street' => 'string',
        'city' => 'string',
        'state' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $adminRules = [
        'street' => 'required',
        'city' => 'required',
        'state' => 'required',
    ];

}
