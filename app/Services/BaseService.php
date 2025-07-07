<?php

namespace App\Services;

abstract class BaseService {
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getAll() {
        return $this->repository->all();
    }

    public function getById($id) {
        return $this->repository->find($id);
    }

    public function store(array $data) {
        return $this->repository->create($data);
    }

    public function update($id, array $data) {
        return $this->repository->update($id, $data);
    }

    public function destroy($id) {
        return $this->repository->delete($id);
    }
}