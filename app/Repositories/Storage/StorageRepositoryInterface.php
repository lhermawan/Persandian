<?php

namespace App\Repositories\Storage;
use App\Repositories\Base\BaseRepository;

interface StorageRepositoryInterface
{
    function store($disk, $path, $curr, $new);

}