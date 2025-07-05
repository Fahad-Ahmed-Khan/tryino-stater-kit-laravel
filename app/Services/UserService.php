<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\BaseService;

class UserService extends BaseService {
    public function __construct(UserRepositoryInterface $repo) {
        parent::__construct($repo);
    }
}
