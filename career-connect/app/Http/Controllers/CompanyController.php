<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class CompanyController extends Controller
{
    // Registracija kompanije
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_description' => 'nullable|string',
            'website' => 'nullable|url',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'company_description' => $request->company_description,
            'website' => $request->website,
            'location' => $request->location,
        ]);

        return response()->json(['message' => 'Company registered successfully'], 201);
    }

    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    // Provera da li je korisnik kompanija
    private function ensureCompany()
    {
        $company = Auth::user()->company;

        if (!$company) {
            return response()->json(['error' => 'Only companies can perform this action.'], 403)->send();
        }

        return $company;
    }

    // Brisanje profila kompanije
    public function destroy()
    {
        $company = $this->ensureCompany();
        if ($company instanceof \Illuminate\Http\JsonResponse) return $company;

        $user = $company->user;
        $company->delete();
        $user->delete();

        return response()->json(['message' => 'Company profile deleted successfully.']);
    }
}