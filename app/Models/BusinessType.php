<?php
/**
 * File name: Currency.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class Currency
 * @package App\Models
 * @version October 22, 2019, 2:46 pm UTC
 *
 * @property string name
 * @property string symbol
 * @property string code
 * @property unsignedTinyInteger decimal_digits
 * @property unsignedTinyInteger rounding
 */
class BusinessType extends Model
{

    public $table = 'business_types';
    


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];    
    
}
