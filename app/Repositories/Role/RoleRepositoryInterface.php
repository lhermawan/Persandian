<?php

namespace App\Repositories\Role;
use App\Repositories\Base\BaseRepository;

interface RoleRepositoryInterface
{
    function status();
    function find(int $id);
    function search(string $field, int $id);
    function get();
    function pagination(int $limit);
    function store($attributes);
    function update($id, $attributes);
    function toggle($attributes);
    function roles($status);

}