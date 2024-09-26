<?php

namespace App\Repositories\Company;
use App\Repositories\Base\BaseRepository;

interface CompanyRepositoryInterface
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