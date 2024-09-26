<?php

namespace App\Repositories\User;
use App\Repositories\BaseRepository;

interface UserRepositoryInterface
{
    function status();
    function find(int $id);
    function search(string $field, int $id);
    function get();
    function pagination(int $limit);
    function store($attributes);
    function update($id, $attributes);
    function updateProfile($id, $attributes);
    function toggle($attributes);
    function changePassword($id, $attributes);

}