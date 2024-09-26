<?php

namespace App\Repositories\Visitor;

use Illuminate\Support\Facades\Hash;
use App\Repositories\BaseRepository;
use App\Repositories\Visitor\VisitorRepositoryInterface;
use App\Repositories\Storage\StorageRepositoryInterface;
use App\Traits\FormatMessageTraits;
use App\Models\Visitor;

class VisitorRepository extends BaseRepository implements VisitorRepositoryInterface
{
    use FormatMessageTraits;

    protected $model;

    /**
     * VisitorRepository constructor.
     * @param
     */
    public function __construct(
        Visitor $visitor,
        StorageRepositoryInterface $storageRepo
    ) {
        // Model
        $this->model = $visitor;

        // StorageRepository
        $this->storageRepo = $storageRepo;

        // STATUS
        $this->STATUS_ACTIVE = config('setting.status.active');
        $this->STATUS_INACTIVE = config('setting.status.notactive');

        // PAGINATION
        $this->PAGE_LIMIT = config('setting.pagination.limit');
        
        // VALUES
        $this->VALUE_EXIST = config('setting.value.exist');
        
        // DISK
        $this->DISK = "public";
        $this->PATH = config('setting.path.profile');

        // MESSAGES TOGGLE
        $this->MESSAGE_TOGGLE_SUCCESS = "%xxx% status successfuly updated.";
        $this->MESSAGE_TOGGLE_FAILED = "%xxx% status failed to update.";
        
        // MESSAGES CREATE
        $this->MESSAGE_CREATE_SUCCESS = "%xxx% successfuly created.";
        $this->MESSAGE_CREATE_FAILED = "%xxx% Failed to create.";
        
        // MESSAGES UPDATE
        $this->MESSAGE_UPDATE_SUCCESS = "%xxx% successfuly updated.";
        $this->MESSAGE_UPDATE_FAILED = "%xxx% Failed to update.";

        // MESSAGES IS EXIST
        $this->MESSAGE_VALUE_EXIST = "Cannot processing action. Data %xxx% still used in another table";
        
        // MESSAGES CHANGE PASS
        $this->MESSAGE_CHANGE_PASS_SUCCESS = "%xxx% password successfuly updated.";
        $this->MESSAGE_CURR_PASS_FAILED = "Incorrect current password.";
        $this->MESSAGE_NEW_PASS_FAILED = "Can not use old password.";
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
     * @param int $limit
     * @return mixed
     */
    public function pagination(int $limit = null)
    {
        if (!$limit) { $limit = $this->PAGE_LIMIT; }

        return (object)[
            'datas' => $this->model->with('role')->paginate($limit),
            'status' => $this->status(),
        ];
    }
    
    

    
    
}