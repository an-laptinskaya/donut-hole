<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allParams = $request->query();
        $sortCols = ['id', 'first_name', 'last_name', 'middle_name', 'salary'];
        $sortBy = 'id';
        $sortDirection = 'ASC';

        $size = isset($allParams["size"]) ? $allParams["size"] : 20;
        if (isset($allParams["sort_by"]) 
            && in_array(strtolower($allParams["sort_by"]), $sortCols)) {
            $sortBy = strtolower($allParams["sort_by"]);
        }
        if (isset($allParams["sort_direction"]) 
            && in_array(strtolower($allParams["sort_direction"]), ['asc', 'desc'])) {
            $sortDirection = strtolower($allParams["sort_direction"]);
        }

        $employee = Employee::orderBy($sortBy, $sortDirection)->paginate($size);

        $employee->getCollection()->transform(function ($employee) {
            return $employee->getResultArray();
        });
    

        return response()->json($employee);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedRequest = $this->validateEmployeeRequest($request);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validatedRequest->errors(),
            ]);
        }

        $employee = new Employee();

        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->middle_name = $request->middle_name;
        $employee->sex = isset($request->sex) ? $request->sex : 0;
        $employee->salary = $request->salary;
        $employee->save();
        $employee->departments()->sync($request->department_ids);
      
        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        if (empty($employee)) {
            $result = ['message' => "Employee with id $id not found"];
        } else {
            $result = $employee->getResultArray();
        }

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $validatedRequest = $this->validateEmployeeRequest($request);

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validatedRequest->errors(),
            ]);
        }

        $employee = Employee::find($id);
        if (empty($employee)) {
            return response()->json(['message' => "Employee with id $id not found"]);
        }

        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'sex' => isset($request->sex) ? $request->sex : 0,
            'salary' => $request->salary,
        ]);

        $employee->departments()->sync($request->department_ids);
      
        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (empty($employee)) {
            return response()->json(['message' => "Employee with id $id not found"]);
        }
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully',
        ]);
    }

    private function validateEmployeeRequest(Request $request)
    {
        return Validator::make($request->all(), [
            "first_name" => ["required", "string"],
            "last_name" => ["required", "string"],
            "middle_name" => ["required", "string"],
            "sex" => ["integer"],
            "salary" => ["required", "integer"],
            "department_ids" => ["required", "array", "min:1"],
            'department_ids.*' => ['integer'],
        ]);
    }
}
