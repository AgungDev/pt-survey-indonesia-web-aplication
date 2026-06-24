<?php

namespace App\Application\DTOs;

class CreateCompanyDTO
{
    public function __construct(
        public string $industryId,
        public string $name,
        public ?string $description,
        public ?string $createdBy,
    ) {
    }
}
