<?php

namespace App\Http\Controllers\API;


use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AddressController
 * @package App\Http\Controllers\API
 */

class AddressAPIController extends Controller
{
    /** @var  AddressRepository */
    private $scheduleRepository;

    public function __construct(AddressRepository $scheduleRepo)
    {
        $this->scheduleRepository = $scheduleRepo;
    }

    /**
     * Display a listing of the Address.
     * GET|HEAD /schedules
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->scheduleRepository->pushCriteria(new RequestCriteria($request));
            $this->scheduleRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $schedules = $this->scheduleRepository->all();

        return $this->sendResponse($schedules->toArray(), 'Addresss retrieved successfully');
    }

    /**
     * Display a listing of the Address.
     * GET|HEAD /schedules
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        try{
            $this->scheduleRepository->pushCriteria(new RequestCriteria($request));
            $this->scheduleRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $count = $this->scheduleRepository->count();

        return $this->sendResponse($count, 'Count retrieved successfully');
    }

    /**
     * Display the specified Address.
     * GET|HEAD /schedules/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Address $schedule */
        if (!empty($this->scheduleRepository)) {
            $schedule = $this->scheduleRepository->findWithoutFail($id);
        }

        if (empty($schedule)) {
            return $this->sendError('Address not found');
        }

        return $this->sendResponse($schedule->toArray(), 'Address retrieved successfully');
    }
    /**
     * Store a newly created Address in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $schedule = $this->scheduleRepository->create($input);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($schedule->toArray(), __('lang.saved_successfully',['operator' => __('lang.address')]));
    }

    /**
     * Update the specified Address in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            return $this->sendError('Address not found');
        }
        $input = $request->all();

        try {
//            $input['options'] = isset($input['options']) ? $input['options'] : [];
            $schedule = $this->scheduleRepository->update($input, $id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($schedule->toArray(), __('lang.saved_successfully',['operator' => __('lang.address')]));
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            return $this->sendError('Address not found');

        }

        $schedule = $this->scheduleRepository->delete($id);

        return $this->sendResponse($schedule, __('lang.deleted_successfully',['operator' => __('lang.address')]));

    }

}
