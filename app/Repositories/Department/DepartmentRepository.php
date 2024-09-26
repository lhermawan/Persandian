<?php

namespace App\Repositories\Department;

use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Traits\FormatMessageTraits;
use App\Models\Department;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    use FormatMessageTraits;

    protected $model;

    /**
     * DepartmentRepository constructor.
     * @param
     */
    public function __construct(Company $company)
    {
        //
        $this->model = $company;

        $this->STATUS_ACTIVE = config('setting.status.active');
        $this->STATUS_INACTIVE = config('setting.status.notactive');
        $this->VALUE_EXIST = config('setting.value.exist');
        $this->VALUE_ZERO = config('setting.value.zero');
        $this->MESSAGE_VALUE_EXIST = "Cannot processing action. Data %xxx% still used in another table";
        $this->MESSAGE_TOGGLE_SUCCESS = "%xxx% status successfuly updated.";
        $this->MESSAGE_TOGGLE_FAILED = "%xxx% status to update.";
        $this->MESSAGE_CREATE_SUCCESS = "%xxx% successfuly created.";
        $this->MESSAGE_CREATE_FAILED = "%xxx% Failed to create.";
        $this->MESSAGE_UPDATE_SUCCESS = "%xxx% successfuly updated.";
        $this->MESSAGE_UPDATE_FAILED = "%xxx% Failed to update.";
    }

    /**
     * @param string $id
     * @param string $message
     * @param object $attributes
     * @return mixed
     */
    public function returnResponse($type, $message, $data)
    {
        return $this->returnMessage($type, $this->format($message, $data->name));
    }

    /**
     * @param none
     * @return mixed
     */
    public function parents()
    {
        return $this->model->where('parent', $this->VALUE_ZERO);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function pagination(int $limit)
    {
        return (object)[
            'datas' => $this->model->paginate($limit),
            'status' => $this->status(),
        ];
    }


    
}