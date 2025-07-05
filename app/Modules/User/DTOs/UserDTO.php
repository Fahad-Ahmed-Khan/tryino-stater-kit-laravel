<?php

namespace App\Modules\User\DTOs;

use App\DTOs\BaseDTO;

class UserDTO extends BaseDTO {
    public string $example;

    public function __construct(array $data) {
        parent::__construct($data);
    }
}
