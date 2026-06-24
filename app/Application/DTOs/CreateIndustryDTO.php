<?php

namespace App\Application\DTOs;

class CreateIndustryDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $createdBy,
    ) {
    }
}
