<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Opening;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Validator;

class StudentController extends Controller
{

    // Registracija studenta
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'faculty' => 'required|string',
            'study_program' => 'required|string',
            'graduation_year' => 'required|integer',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Student::create([
            'user_id' => $user->id,
            'faculty' => $request->faculty,
            'study_program' => $request->study_program,
            'graduation_year' => $request->graduation_year,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Student registered successfully'], 201);
    }
    
    // Provera da li je korisnik student
    private function ensureStudent()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return response()->json(['error' => 'Only students can perform this action.'], 403)->send();
        }

        return $student;
    }

    // Prijava na oglas
    public function apply(Request $request, $id)
    {
        $student = $this->ensureStudent();
        if ($student instanceof \Illuminate\Http\JsonResponse) return $student;

        $opening = Opening::findOrFail($id);

        if ($student->applications()->where('opening_id', $id)->exists()) {
            return response()->json(['error' => 'You have already applied for this job.'], 400);
        }

        Application::create([
            'student_id' => $student->id,
            'opening_id' => $opening->id,
        ]);

        return response()->json(['message' => 'Application submitted successfully.']);
    }


    // Brisanje naloga
    public function destroy($studentId = null)
{
    $user = Auth::user();

    // Ako je student, briše svoj nalog
    if ($user->student && is_null($studentId)) {
        $student = $this->ensureStudent();
        if ($student instanceof \Illuminate\Http\JsonResponse) return $student;

        $user = $student->user;
        $student->delete();
        $user->delete();

        return response()->json(['message' => 'Profile deleted successfully.']);
    }

    // Ako je admin, omogućava mu da obriše bilo kog studenta po ID-ju
    if ($user->admin && !is_null($studentId)) {
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['error' => 'Student not found.'], 404);
        }

        $user = $student->user;
        $student->delete();
        $user->delete();

        return response()->json(['message' => 'Student profile deleted successfully by admin.']);
    }

    return response()->json(['error' => 'Unauthorized'], 403);
}
}
