<?php

namespace App\Services;

class BaseModelService
{
    /**
     * The model class name.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new service instance.
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Get the model instance.
     *
     * @return mixed
     */
    public function getModel()
    {
        return app($this->model);
    }

    /**
     * Get the model class name.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->model;
    }

    /**
     * Get the model table name.
     *
     * @return string
     */
    public function getModelTable()
    {
        return app($this->model)->getTable();
    }

    /**
     * Get the model primary key.
     *
     * @return string
     */
    public function getModelPrimaryKey()
    {
        return app($this->model)->getKeyName();
    }
    /**
     * Get the model fillable attributes.
     *
     * @return array
     */
    public function getModelFillable()
    {   
        return app($this->model)->getFillable();
    }

    // crud operations 
    
    public function create(array $data)
    {
        return $this->getModel()->create($data);
    }
    
    public function update($id, array $data)
    {
        $model = $this->getModel()->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->getModel()->findOrFail($id);
        return $model->delete();
    }

    public function find($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    public function paginate($perPage = 15)
    {
        return $this->getModel()->paginate($perPage);
    }   

    public function where(array $conditions)
    {
        $query = $this->getModel();
        foreach ($conditions as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->get();
    }
}   