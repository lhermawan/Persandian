<?php

namespace App\Repositories\Storage;

use Illuminate\Support\Facades\Storage;
use App\Repositories\Storage\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{

    /**
     * StorageRepository constructor.
     * @param
     */
    public function __construct()
    {
        $this->DEFAULT_FILE = 'default.png';
    }

    /**
     * @param string $disk
     * @param string $path
     * @param string $file
     * @return mixed
     */
    public function exist($disk, $path, $file = '')
    {
        return Storage::disk($disk)->exists($path.$file);
    }

    /**
     * @param string $disk
     * @param string $path
     * @return mixed
     */
    public function makeDirectory($disk, $path)
    {
        return Storage::disk($disk)->makeDirectory($path);
    }

    /**
     * @param string $disk
     * @param string $path
     * @param string $file
     * @return mixed
     */
    public function remove($disk, $path, $file)
    {
        if($file != $this->DEFAULT_FILE)
        {
            return Storage::disk($disk)->delete($path.$file);
        }
        
    }

    /**
     * @param string $disk
     * @param string $path
     * @param string $curr
     * @param object $new
     * @return mixed
     */
    public function store($disk, $path, $curr, $new)
    {
        // Remove unused file
        if($this->exist($disk, $path, $curr)) {
            $this->remove($disk, $path, $curr);
        }

        // If directory doesnt exist
        if(!$this->exist($disk, $path)) {
            $this->makeDirectory($disk, $path);
        }

        return Storage::disk($disk)->putFileAs($path, $new->file, $new->filename);

    }

    



    
}