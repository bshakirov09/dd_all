<?php

namespace App\Repositories;

use App\Models\Address;
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
class AddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'apartment',
        'street',
        'city',
        'state'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Address::class;
    }
}
