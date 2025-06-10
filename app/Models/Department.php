<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name'
    ];

    public function getResultArray()
    {
        $maxSalary = array_map(function ($item) {
            return $item['salary'];
        }, $this->employees->toArray());
        
        return [
            'id' => $this->id, 
            'department_name' => $this->department_name, 
            'employees_count' => count($this->employees),
            'max_salary' => max($maxSalary), 
        ];
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'departments_employees');
    }
}
