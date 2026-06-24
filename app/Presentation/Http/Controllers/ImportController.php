<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\ImportEquipmentDTO;
use App\Application\UseCases\ApproveImportUseCase;
use App\Application\UseCases\ImportEquipmentUseCase;
use App\Application\UseCases\PreviewImportUseCase;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Models\Company;
use App\Models\Industry;
use App\Presentation\Http\Requests\ImportApprovalRequest;
use App\Presentation\Http\Requests\ImportEquipmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ImportController extends Controller
{
    public function __construct(
        private ImportEquipmentUseCase $importUseCase,
        private ImportHistoryRepositoryInterface $historyRepository,
        private PreviewImportUseCase $previewImportUseCase,
        private ApproveImportUseCase $approveImportUseCase,
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

        return Redirect::route('imports.index')->with('status', "Import submitted and pending review (id: {$historyId}).");
    }

    public function show(string $id)
    {
        $preview = $this->previewImportUseCase->execute($id);

        if (!$preview['history']) {
            return Redirect::route('imports.index')->with('error', 'Import preview not found.');
        }

        return View::make('imports.show', [
            'preview' => $preview,
            'history' => $preview['history'],
            'industries' => Industry::orderBy('name')->get(),
            'companies' => Company::with('industry')->orderBy('name')->get(),
        ]);
    }

    public function approve(string $id, ImportApprovalRequest $request)
    {
        $approved = $this->approveImportUseCase->execute(
            $id,
            Auth::id(),
            $request->input('industry_id'),
            $request->input('company_id'),
            $request->input('comment')
        );

        if (!$approved) {
            return Redirect::route('imports.show', $id)->with('error', 'Unable to approve import. Please verify the import is still pending review.');
        }

        return Redirect::route('imports.index')->with('status', 'Import approved and queued for execution.');
    }

    public function reject(string $id, ImportApprovalRequest $request)
    {
        $history = $this->historyRepository->find($id);

        if (!$history || $history->status !== 'Pending Review') {
            return Redirect::route('imports.show', $id)->with('error', 'Import cannot be rejected at this stage.');
        }

        $this->historyRepository->updateStatus($id, [
            'status' => 'Rejected',
            'review_comment' => $request->input('comment'),
            'reviewed_at' => now(),
        ]);

        return Redirect::route('imports.index')->with('status', 'Import rejected.');
    }
}
