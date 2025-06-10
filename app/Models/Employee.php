<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'sex',
        'salary',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function getSexStringAttribute()
    {
        switch ($this->sex) {
            case 1:
                return 'Male';

            case 2:
                return 'Female';

            default:
                return 'Not known';
        }
    }

    public function getResultArray()
    {
        return [
            'id' => $this->id, 
            'full_name' => $this->full_name, 
            'sex' => $this->sex_string, 
            'salary' => $this->salary, 
        ];
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'departments_employees');
    }
}
