<?php

namespace App\Modules\User\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\User\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface {
    public function __construct(User $model) {
        parent::__construct($model);
    }
}
