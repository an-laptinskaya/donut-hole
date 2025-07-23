<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allParams = $request->query();
        $sortCols = ['id', 'department_name'];
        $sortBy = 'id';
        $sortDirection = 'ASC';

        $size = isset($allParams["size"]) ? $allParams["size"] : 10;
        if (isset($allParams["sort_by"]) 
            && in_array(strtolower($allParams["sort_by"]), $sortCols)) {
            $sortBy = strtolower($allParams["sort_by"]);
        }
        if (isset($allParams["sort_direction"]) 
            && in_array(strtolower($allParams["sort_direction"]), ['asc', 'desc'])) {
            $sortDirection = strtolower($allParams["sort_direction"]);
        }

        $department = Department::with('employees')->orderBy($sortBy, $sortDirection)->paginate($size);

        $department->getCollection()->transform(function ($department) {
            return $department->getResultArray();
        });
    

        return response()->json($department);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedRequest = $this->validateDepartmentRequest($request);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validatedRequest->errors(),
            ]);
        }

        $department = new Department();

        $department->department_name = $request->department_name;
        $department->save();
      
        return response()->json([
            'status' => 'success',
            'message' => 'Department created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::with('employees')->find($id);

        if (empty($department)) {
            $result = ['message' => "Department with id $id not found"];
        } else {
            $result = $department->getResultArray();
        }

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $validatedRequest = $this->validateDepartmentRequest($request);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validatedRequest->errors(),
            ]);
        }

        $department = Department::find($id);
        if (empty($department)) {
            return response()->json(['message' => "Department with id $id not found"]);
        }

        $department->update([
            'department_name' => $request->department_name,
        ]);
      
        return response()->json([
            'status' => 'success',
            'message' => 'Department updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::with('employees')->find($id);
        if (empty($department)) {
            return response()->json(['message' => "Department with id $id not found"]);
        }
        if (count($department->employees) > 0) {
            return response()->json(['message' => "Department with id $id has employees, can not be deleted"]);
        }
        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Department deleted successfully',
        ]);
    }

    private function validateDepartmentRequest(Request $request)
    {
        return Validator::make($request->all(), [
            "department_name" => ["required", "string"]
        ]);
    }
}
