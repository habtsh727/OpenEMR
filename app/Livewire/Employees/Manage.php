<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Manage extends Component
{
    use WithPagination;

    public $showModal = false;
    public $search = '';
    public $employeeId = null;
    
    // Form fields
    public $user_id;
    public $role;
    public $employee_code;
    public $status = 'active';
    
    // Data for selects
    public $users = [];
    public $roles = [];

    public function mount()
    {
        $this->loadSelectData();
    }

    public function loadSelectData()
    {
        // Get users without employee records
        $this->users = User::doesntHave('employee')->get();
        
        // For edit mode, include the current user
        if ($this->employeeId) {
            $currentEmployee = Employee::find($this->employeeId);
            if ($currentEmployee && $currentEmployee->user) {
                $this->users->push($currentEmployee->user);
            }
        }
        
        $this->roles = Role::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->loadSelectData();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        
        $this->employeeId = $employee->id;
        $this->user_id = $employee->user_id;
        $this->role = $employee->role;
        $this->employee_code = $employee->employee_code;
        $this->status = $employee->status;
        
        $this->loadSelectData();
        $this->showModal = true;
    }

    public function save()
    {
        // Dynamic validation rules
        $rules = [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string',
            'employee_code' => 'required|string',
            'status' => 'required|in:active,inactive',
        ];

        // Add unique constraints for create/update
        if ($this->employeeId) {
            $rules['user_id'] .= '|unique:employees,user_id,' . $this->employeeId;
            $rules['employee_code'] .= '|unique:employees,employee_code,' . $this->employeeId;
        } else {
            $rules['user_id'] .= '|unique:employees,user_id';
            $rules['employee_code'] .= '|unique:employees,employee_code';
        }

        $this->validate($rules);

        // Prepare data
        $employeeData = [
            'user_id' => $this->user_id,
            'role' => $this->role,
            'employee_code' => $this->employee_code,
            'status' => $this->status,
        ];

        // Save or update
        if ($this->employeeId) {
            $employee = Employee::find($this->employeeId);
            $employee->update($employeeData);
            $message = 'Employee updated successfully!';
        } else {
            Employee::create($employeeData);
            $message = 'Employee created successfully!';
        }

        // Sync role with user
        $user = User::find($this->user_id);
        if ($user && $this->role) {
            $user->syncRoles([$this->role]);
        }

        $this->closeModal();
        session()->flash('success', $message);
    }

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Remove role from user
        if ($employee->user) {
            $employee->user->roles()->detach();
        }
        
        $employee->delete();
        
        session()->flash('success', 'Employee deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['employeeId', 'user_id', 'role', 'employee_code', 'status']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $employees = Employee::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_code', 'like', "%{$this->search}%")
                      ->orWhere('role', 'like', "%{$this->search}%")
                      ->orWhere('status', 'like', "%{$this->search}%")
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', "%{$this->search}%")
                                    ->orWhere('email', 'like', "%{$this->search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.employees.manage', compact('employees'));
    }
}