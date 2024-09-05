<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ApplicationController extends Controller
{
    // Promena statusa prijave
    public function updateStatus(Request $request, $applicationId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:applied,interviewing,accepted,rejected',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $application = Application::findOrFail($applicationId);
        $company = Auth::user()->company;
        if (
            !$company || $application->opening->company_id !==
            $company->id
        ) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }
        $application->update([
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Application status updated successfully.']);
    }
    // Lista svih prijava (samo admin može da vidi)
    public function indexForAdmin()
    {
        if (!Auth::user()->admin) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }
        $applications = Application::all();
        return response()->json($applications);
    }
    // Brisanje prijave (samo admin može da izvede)
    public function destroy($applicationId)
    {
        if (!Auth::user()->admin) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }
        $application = Application::findOrFail($applicationId);
        $application->delete();
        return response()->json(['message' => 'Application deleted successfully.']);
    }
    // Lista prijava za određeni oglas (samo kompanija može da vidi)
    public function indexForOpening($openingId)
    {
        $company = Auth::user()->company;
        if (!$company) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }
        $opening = $company->openings()->where('id', $openingId)->first();
        if (!$opening) {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }
        $applications = $opening->applications;
        return new ApplicationCollection($applications);
    }
}
