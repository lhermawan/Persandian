<?php

namespace App\Repositories\Role;

use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Traits\FormatMessageTraits;
use App\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    use FormatMessageTraits;

    protected $model;

    /**
     * RoleRepository constructor.
     * @param
     */
    public function __construct(Role $role)
    {
        //

        $this->model = $role;

        $this->STATUS_ACTIVE = config('setting.status.active');
        $this->STATUS_INACTIVE = config('setting.status.notactive');
        $this->VALUE_EXIST = config('setting.value.exist');
        $this->MESSAGE_VALUE_EXIST = "Cannot processing action. Data %xxx% still used in another table";
        $this->MESSAGE_TOGGLE_SUCCESS = "%xxx% status successfuly updated.";
        $this->MESSAGE_TOGGLE_FAILED = "%xxx% status failed to update.";
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

    public function roles($status)
    {
        $data = $this->model->query();
        
        if( $status != config('setting.status.all') ) {
            $data->where('status', $status);
        }

        return $data;
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
    
    /**
     * @param object $attributes
     * @return mixed
     */
    public function toggle($attributes)
    {
        $role = $this->find($attributes->id);
        if($role->users()->count() >= $this->VALUE_EXIST)
        {
            return $this->returnResponse('warning', $this->MESSAGE_VALUE_EXIST, $role);
        }
        
        $role->fill( $attributes->toArray() );
        if(!$role->save())
        {
            return $this->returnResponse('warning', $this->MESSAGE_TOGGLE_FAILED, $role);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_TOGGLE_SUCCESS, $role);
        }
    }

    
}