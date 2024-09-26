<?php

namespace App\Repositories\Department;
use App\Repositories\Base\BaseRepository;

interface DesaRepositoryInterface
{
    function status();
    function find(int $id);
    function search(string $field, int $id);
    function get();
    function parents();
    function pagination(int $limit);
    function store($attributes);
    function update($id, $attributes);
    function toggle($attributes);

}
