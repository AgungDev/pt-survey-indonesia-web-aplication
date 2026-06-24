<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateIndustryDTO;
use App\Domain\Repositories\IndustryRepositoryInterface;

class CreateIndustryUseCase
{
    public function __construct(
        private IndustryRepositoryInterface $industryRepository,
    ) {
    }

    public function execute(CreateIndustryDTO $dto)
    {
        return $this->industryRepository->create([
            'name' => $dto->name,
            'description' => $dto->description,
            'created_by' => $dto->createdBy,
        ]);
    }
}
