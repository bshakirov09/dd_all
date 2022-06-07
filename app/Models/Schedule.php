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
class Schedule extends Model
{
    public $table = 'schedules';
    public $fillable = [
        'order_id',
        'time',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'string',
        'time' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $adminRules = [
        'order_id' => 'required',
        'time' => 'required',
    ];

    public function order()
    {
        return $this->morphMany('App\Models\Order', 'orderable');
    }

}
