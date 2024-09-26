<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Hash;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Storage\StorageRepositoryInterface;
use App\Traits\FormatMessageTraits;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    use FormatMessageTraits;

    protected $model;

    /**
     * UserRepository constructor.
     * @param
     */
    public function __construct(
        User $user,
        StorageRepositoryInterface $storageRepo
    ) {
        // Model
        $this->model = $user;

        // StorageRepository
        $this->storageRepo = $storageRepo;

        // STATUS
        $this->STATUS_NOTUSE = config('setting.status.notuse');
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
            'datas' => $this->model->with('role')->where('status','!=','0')->paginate($limit),
            'status' => $this->status(),
        ];
    }
    
    /**
     * @param array $attributes
     * @return mixed
     */
    public function updateProfile($id, $attributes)
    {   
        // Set user first
        $user = $this->find($id);
        $data = $attributes->toArray();

        if( !$attributes->hasFile('image') ){
            $attributes->except(['image']);
            // removes the image key from the request if image field is empty
        }
        
        if( $attributes->hasFile('image') ){
            // Uploading Image
            $images = (object)[
                'filename' => $attributes->file('image')->getClientOriginalName(),
                'file' => $attributes->image
            ];
            $this->storageRepo->store($this->DISK, $this->PATH, $user->image, $images);
            
            $data['image'] = $images->filename;
        }
        
        $user->fill( $data );
        if(!$user->save())
        {
            return $this->returnResponse('warning', $this->MESSAGE_UPDATE_FAILED, $user);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_UPDATE_SUCCESS, $user);
        }
    }

    /**
     * @param object $attributes
     * @return mixed
     */
    public function changePassword($id, $attributes)
    {
        $user = $this->find($id);
        
        if (!(Hash::check($attributes->current_password, $user->password))) {
            // The passwords didnt matches
            return $this->returnMessage('warning', $this->format($this->MESSAGE_CURR_PASS_FAILED));
        }
        
        if (Hash::check($attributes->new_password, $user->password)) {
            // Current password and new password are same
            return $this->returnMessage('warning', $this->format($this->MESSAGE_NEW_PASS_FAILED));
        }
        
        //Change Password
        $user->fill( $attributes->toArray() );
        $user->save();

        return $this->returnResponse('warning', $this->MESSAGE_CHANGE_PASS_SUCCESS, $user);

    }
    
}