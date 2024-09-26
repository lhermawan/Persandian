<?php

namespace App\Repositories\AccessControl;

use App\Repositories\AccessControl\AccessControlRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Traits\FormatMessageTraits;
use App\Models\Role;

class AccessControlRepository implements AccessControlRepositoryInterface
{
    use FormatMessageTraits;

    /**
     * AccessControlRepository constructor.
     * @param
     */
    public function __construct(
        RoleRepositoryInterface $roleRepo,
        MenuRepositoryInterface $menuRepo
    )
    {
        $this->roleRepo = $roleRepo;
        $this->menuRepo = $menuRepo;
        
        $this->MESSAGE_UPDATE_SUCCESS = "Access Control to %xxx% Role has been updated.";
        $this->MESSAGE_UPDATE_FAILED = "Access Control to %xxx% Role failed to updated.";
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function update($attributes)
    {
        $role = $this->roleRepo->find($attributes->role);
        if(!$role->menuses()->sync($attributes->menus))
        {
            return $this->returnMessage('warning', $this->format($this->MESSAGE_UPDATE_FAILED, $role->name));
        } else {
            return $this->returnMessage('success', $this->format($this->MESSAGE_UPDATE_SUCCESS, $role->name));
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function access($id)
    {
        $role   = $this->roleRepo->find($id);
        $pivot  = $role->menuses()->get();

        $hasAccess = [];
		foreach ($pivot as $menus) {
			$hasAccess[] = $menus->id;
			#or first convert it and then change its properties using 
			#an array syntax, it's up to you
        }
        return (object)[
            'menus'  => $this->menuRepo->get(),
            'pivot'  => $hasAccess,
        ];
    }

    
}