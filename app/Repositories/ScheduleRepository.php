<?php

namespace App\Repositories;

use App\Models\Schedule;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ScheduleRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Schedule findWithoutFail($id, $columns = ['*'])
 * @method Schedule find($id, $columns = ['*'])
 * @method Schedule first($columns = ['*'])
*/
class ScheduleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'time',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Schedule::class;
    }
}
