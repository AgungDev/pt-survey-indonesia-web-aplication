<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\CreateCompanyDTO;
use App\Application\UseCases\CreateCompanyUseCase;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\Repositories\IndustryRepositoryInterface;
use App\Presentation\Http\Requests\CreateCompanyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
        private IndustryRepositoryInterface $industryRepository,
        private CreateCompanyUseCase $createCompanyUseCase,
    ) {
    }

    public function index()
    {
        $companies = $this->companyRepository->all();

        return View::make('pages.companies.index', compact('companies'));
    }

    public function create()
    {
        $industries = $this->industryRepository->all();

        return View::make('pages.companies.create', compact('industries'));
    }

    public function store(CreateCompanyRequest $request)
    {
        $company = $this->createCompanyUseCase->execute(new CreateCompanyDTO(
            $request->input('industry_id'),
            $request->input('name'),
            $request->input('description'),
            Auth::id(),
        ));

        return Redirect::route('companies.index')->with('status', "Company {$company->name} created.");
    }
}
