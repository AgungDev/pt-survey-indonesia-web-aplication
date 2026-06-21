<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\ImportEquipmentDTO;
use App\Application\UseCases\ImportEquipmentUseCase;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Presentation\Http\Requests\ImportEquipmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ImportController extends Controller
{
    public function __construct(
        private ImportEquipmentUseCase $importUseCase,
        private ImportHistoryRepositoryInterface $historyRepository,
    ) {
    }

    public function index()
    {
        $histories = $this->historyRepository->recent(10);

        return View::make('imports.index', compact('histories'));
    }

    public function store(ImportEquipmentRequest $request)
    {
        $historyId = $this->importUseCase->execute(new ImportEquipmentDTO(
            $request->file('file'),
            Auth::id(),
        ));

        return Redirect::route('imports.index')->with('status', "Import submitted (id: {$historyId}).");
    }
}
