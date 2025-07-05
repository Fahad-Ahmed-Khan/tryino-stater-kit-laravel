<?php

namespace App\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\User\Services\UserService;

class UserController extends Controller {
    protected $service;

    public function __construct(UserService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getAll();
    }

    public function store(Request $request) {
        return $this->service->store($request->all());
    }

    public function show($id) {
        return $this->service->getById($id);
    }

    public function update(Request $request, $id) {
        return $this->service->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->service->destroy($id);
    }
}
