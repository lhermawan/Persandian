<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;   
    }

    /**
     * @param
     * @return array
     */
    public function status()
    {
        return [
            $this->STATUS_ACTIVE     => __('ACTIVE'),
            $this->STATUS_INACTIVE   => __('NOT ACTIVE'),
        ];
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param string $field
     * @param integer $id
     * @return mixed
     */
    public function search(string $field, int $id)
    {
        return $this->model->where($field, $id);
    }

    /**
     * @param none
     * @return mixed
     */
    public function get()
    {
        return $this->model->where('status', $this->STATUS_ACTIVE)->get();
    }
    
    /**
     * @param none
     * @return mixed
     */
    public function list()
    {
        return $this->model;
    }

    /**
     * @param object $attributes
     * @return mixed
     */
    public function toggle($attributes)
    {
        $data = $this->find($attributes->id);
        $data->fill( $attributes->toArray() );
        if( !$data->save() )
        {
            return $this->returnResponse('warning', $this->MESSAGE_TOGGLE_FAILED, $data);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_TOGGLE_SUCCESS, $data);
        }
    }

    /**
     * @param object $attributes
     * @return mixed
     */
    public function store($attributes)
    {
        $data = new $this->model;
        $data->fill( $attributes->toArray() );
        if( !$data->save() )
        {
            return $this->returnResponse('warning', $this->MESSAGE_CREATE_FAILED, $data);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_CREATE_SUCCESS, $data);
        }
    }

    /**
     * @param integer $id
     * @param object $attributes
     * @return mixed
     */
    public function update($id, $attributes)
    {
        $data = $this->find($id);
        $data->fill( $attributes->toArray() );
        if( !$data->save() )
        {
            return $this->returnResponse('warning', $this->MESSAGE_UPDATE_FAILED, $data);
        } else {
            return $this->returnResponse('success', $this->MESSAGE_UPDATE_SUCCESS, $data);
        }
    }







}