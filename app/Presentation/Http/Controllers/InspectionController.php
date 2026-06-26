<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\CreateInspectionDTO;
use App\Application\UseCases\ApproveInspectionUseCase;
use App\Application\UseCases\CreateInspectionUseCase;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;
use App\Models\User;
use App\Presentation\Http\Requests\StoreInspectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class InspectionController extends Controller
{
    public function __construct(
        private InspectionRepositoryInterface $inspectionRepository,
        private EquipmentRepositoryInterface $equipmentRepository,
        private CreateInspectionUseCase $createInspectionUseCase,
        private ApproveInspectionUseCase $approveInspectionUseCase,
    ) {
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }

        if (Auth::user()->hasRole('Inspector')) {
            $filters['inspector_id'] = Auth::id();
        }

        $inspections = $this->inspectionRepository->paginate($filters, 15);

        return View::make('inspections.index', compact('inspections'));
    }

    public function show(string $id)
    {
        $inspection = $this->inspectionRepository->find($id);

        if (!$inspection) {
            return Redirect::route('inspections.index')->with('error', 'Inspection not found.');
        }

        return View::make('inspections.show', compact('inspection'));
    }

    public function create()
    {
        $equipments = $this->equipmentRepository->all();
        $inspectors = null;

        if (! Auth::user()->hasRole('Inspector')) {
            $inspectors = User::whereHas('role', function ($query) {
                $query->where('name', 'Inspector');
            })->orderBy('name')->get();
        }

        return View::make('inspections.create', compact('equipments', 'inspectors'));
    }

    public function store(StoreInspectionRequest $request)
    {
        $inspectorId = Auth::user()->hasRole('Inspector')
            ? Auth::id()
            : $request->input('inspector_id');

        $dto = new CreateInspectionDTO(
            surveyTimestamp: $request->input('survey_timestamp'),
            equipmentId: $request->input('equipment_id'),
            inspectorId: $inspectorId,
            inspectionType: $request->input('inspection_type'),
            inspectionResult: $request->input('inspection_result'),
            recommendation: $request->input('recommendation'),
            unitPhoto: $request->file('unit_photo'),
            findings: $request->input('findings', []),
            findingPhotos: $request->file('finding_photos', []),
        );

        $inspection = $this->createInspectionUseCase->execute($dto);

        return Redirect::route('inspections.index')->with('status', "Inspection {$inspection->id} submitted.");
    }

    public function approve(string $id)
    {
        $this->approveInspectionUseCase->execute($id, 'Approved', Auth::id());

        return Redirect::route('inspections.index')->with('status', 'Inspection approved.');
    }

    public function reject(string $id)
    {
        $this->approveInspectionUseCase->execute($id, 'Rejected', Auth::id());

        return Redirect::route('inspections.index')->with('status', 'Inspection rejected.');
    }
}
