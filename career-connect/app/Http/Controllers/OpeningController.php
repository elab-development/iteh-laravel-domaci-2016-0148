<?php

namespace App\Http\Controllers;

use App\Models\Opening;
use Auth;
use Illuminate\Http\Request;
use Validator;

class OpeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Opening::query();
        if ($request->has('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        if ($request->has('work_mode')) {
            $query->where('work_mode', $request->work_mode);
        }
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        return response()->json($query->get(), 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'employment_type' => 'required|string|in:fulltime,part-time,internship',
            'work_mode' => 'required|string|
in:office,hybrid,remote',
            'expires_at' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $company = Auth::user()->company;
        if (!$company) {
            return response()->json(['error' => 'Only companies
can create openings.'], 403);
        }
        $opening = Opening::create([
            'company_id' => $company->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'work_mode' => $request->work_mode,
            'expires_at' => $request->expires_at,
        ]);
        return response()->json($opening, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $opening = Opening::findOrFail($id);
        return response()->json($opening);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $opening = Opening::findOrFail($id);
        $company = Auth::user()->company;
        if (!$company || $opening->company_id != $company->id) {
            return response()->json(
                ['error' => 'Unauthorized.'],
                403
            );
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string',
            'employment_type' => 'sometimes|string|in:fulltime,part-time,internship',
            'work_mode' => 'sometimes|string|
in:office,hybrid,remote',
            'expires_at' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $opening->update($request->all());
        return response()->json($opening);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $opening = Opening::findOrFail($id);
        $company = Auth::user()->company;
        if (!$company || $opening->company_id != $company->id) {
            return response()->json(
                ['error' => 'Unauthorized.'],
                403
            );
        }
        $opening->delete();
        return response()->json(['message' => 'Opening deleted
successfully.']);
    }
}
