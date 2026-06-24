<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\CreateIndustryDTO;
use App\Application\UseCases\CreateIndustryUseCase;
use App\Domain\Repositories\IndustryRepositoryInterface;
use App\Presentation\Http\Requests\CreateIndustryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class IndustryController extends Controller
{
    public function __construct(
        private IndustryRepositoryInterface $industryRepository,
        private CreateIndustryUseCase $createIndustryUseCase,
    ) {
    }

    public function index()
    {
        $industries = $this->industryRepository->all();

        return View::make('pages.industries.index', compact('industries'));
    }

    public function create()
    {
        return View::make('pages.industries.create');
    }

    public function store(CreateIndustryRequest $request)
    {
        $industry = $this->createIndustryUseCase->execute(new CreateIndustryDTO(
            $request->input('name'),
            $request->input('description'),
            Auth::id(),
        ));

        return Redirect::route('industries.index')->with('status', "Industry {$industry->name} created.");
    }
}
