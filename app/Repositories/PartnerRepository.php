<?php

namespace App\Repositories;

use App\Models\Partner;
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
class PartnerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'business_name',
        'business_address',
        'business_type',
        'full_name',
        'position',
        'email',
        'phone_number',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Partner::class;
    }
}
