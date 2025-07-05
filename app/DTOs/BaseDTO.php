<?php

namespace App\Traits\API;

abstract class BaseDTO {
    public function __construct(array $attributes) {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {

                $this->{$key} = $value;
            }
        }
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

    /**
     * Convert the DTO to a JSON string.
     *
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray());
    }

    /**
     * Convert the DTO to a string representation.
     *
     * @return string
     */
    public function __toString(): string {
        return $this->toJson();
    }

}