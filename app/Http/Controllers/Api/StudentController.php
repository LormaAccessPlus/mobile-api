<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AccessApi\AccessApiException;
use App\Services\AccessApi\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function getInfo(Request $request, string $studentId): JsonResponse
    {
        try {
            $request->validate([
                'params' => 'sometimes|array',
                'params.*' => 'string|in:enroll,numbers,contacts,balance'
            ]);

            $student = new Student($studentId);
            $params = $request->get('params', []);
            
            $data = $student->getInfo($params);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function authenticate(Request $request, string $studentId): JsonResponse
    {
        try {
            $request->validate([
                'password' => 'required|string'
            ]);

            $student = new Student($studentId);
            $isAuthenticated = $student->authenticate($request->password);
            
            return response()->json([
                'success' => true,
                'authenticated' => $isAuthenticated
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCurriculum(string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getCurriculum();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getGrades(Request $request, string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getGrades($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTermGrades(Request $request, string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getTermGrades($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSchedule(Request $request, string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getSchedule($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAssessment(string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getAssessment();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBalance(string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getBalance();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLedgerHistory(string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->getLedgerHistory();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function assess(string $studentId): JsonResponse
    {
        try {
            $student = new Student($studentId);
            $data = $student->assess();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (AccessApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}