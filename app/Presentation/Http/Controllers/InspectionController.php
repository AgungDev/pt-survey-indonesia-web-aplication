<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\CreateInspectionDTO;
use App\Application\UseCases\ApproveInspectionUseCase;
use App\Application\UseCases\CreateInspectionUseCase;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;
use App\Presentation\Http\Requests\StoreInspectionRequest;
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

    public function index()
    {
        $inspections = $this->inspectionRepository->paginate([], 15);

        return View::make('inspections.index', compact('inspections'));
    }

    public function create()
    {
        $equipments = $this->equipmentRepository->all();

        return View::make('inspections.create', compact('equipments'));
    }

    public function store(StoreInspectionRequest $request)
    {
        $dto = new CreateInspectionDTO(
            surveyTimestamp: $request->input('survey_timestamp'),
            equipmentId: $request->input('equipment_id'),
            inspectorId: Auth::id(),
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
