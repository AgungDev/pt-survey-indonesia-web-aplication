<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateCompanyDTO;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\Repositories\IndustryRepositoryInterface;

class CreateCompanyUseCase
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
        private IndustryRepositoryInterface $industryRepository,
    ) {
    }

    public function execute(CreateCompanyDTO $dto)
    {
        $industry = $this->industryRepository->find($dto->industryId);

        if (!$industry) {
            throw new \InvalidArgumentException('Industry not found.');
        }

        return $this->companyRepository->create([
            'industry_id' => $dto->industryId,
            'name' => $dto->name,
            'description' => $dto->description,
            'created_by' => $dto->createdBy,
        ]);
    }
}
