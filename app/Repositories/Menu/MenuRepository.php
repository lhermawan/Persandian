<?php

namespace App\Repositories\Menu;

use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Traits\FormatMessageTraits;
use App\Models\Menu;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    use FormatMessageTraits;

    protected $model;

    /**
     * MenuRepository constructor.
     * @param
     */
    public function __construct( Menu $menu)
    {
        // 
        $this->model = $menu;

        $this->STATUS_ACTIVE = config('setting.status.active');
        $this->STATUS_INACTIVE = config('setting.status.notactive');
        $this->VALUE_EXIST = config('setting.value.exist');
        $this->VALUE_ZERO = config('setting.value.zero');
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
        return $this->returnMessage($type, $this->format($message, $data->label));
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
        $menus = $this->model
            ->select('menus.*', 'parent.label as parent_label')
            ->leftjoin('menus as parent', 'parent.id', '=', 'menus.parent')
            ->orderByRaw('COALESCE(parent.id, menus.id), menus.parent, menus.order')
            ->paginate($limit);

        return (object)[
            'datas' => $menus,
            'status' => $this->status(),
        ];
    }

    /**
     * @param object $attributes
     * @return mixed
     */
    public function toggle($attributes)
    {
        $menu = $this->find($attributes->id);
        if($menu->roles()->count() >= $this->VALUE_EXIST)
        {
            return $this->returnResponse('warning', $this->MESSAGE_VALUE_EXIST, $menu);
        }
        $menu->fill( $attributes->toArray() );
        if(!$menu->save())
        {
            return $this->returnResponse('warning', $this->MESSAGE_TOGGLE_FAILED, $menu);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_TOGGLE_SUCCESS, $menu);
        }
    }

    
}