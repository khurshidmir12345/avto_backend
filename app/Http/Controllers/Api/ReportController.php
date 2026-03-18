<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'reportable_type' => ['required', 'string', Rule::in(array_keys(Report::getReportableTypes()))],
            'reportable_id' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', Rule::in(Report::REASONS)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $modelClass = Report::resolveReportableType($request->reportable_type);

        if (!$modelClass || !$modelClass::find($request->reportable_id)) {
            return response()->json(['message' => 'Report qilinadigan kontent topilmadi'], 404);
        }

        $existingReport = Report::where('reporter_id', $user->id)
            ->where('reportable_type', $modelClass)
            ->where('reportable_id', $request->reportable_id)
            ->where('status', Report::STATUS_PENDING)
            ->exists();

        if ($existingReport) {
            return response()->json(['message' => 'Siz bu kontent haqida allaqachon shikoyat yuborgansiz'], 409);
        }

        $report = Report::create([
            'reporter_id' => $user->id,
            'reportable_type' => $modelClass,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Shikoyatingiz qabul qilindi. Tez orada ko\'rib chiqamiz.',
            'report' => $report,
        ], 201);
    }

    public function myReports(Request $request): JsonResponse
    {
        $reports = Report::where('reporter_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($reports);
    }
}
