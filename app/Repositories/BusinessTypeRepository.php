<?php

namespace App\Repositories;

use App\Models\BusinessType;
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
class BusinessTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BusinessType::class;
    }
}
