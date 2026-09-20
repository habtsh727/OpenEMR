<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    public $roles;
    public $permissions;

    public $name, $selectedPermissions = [], $roleId;
    public $editMode = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function resetForm()
    {
        $this->reset(['name','selectedPermissions','roleId','editMode']);
    }

    public function createRole()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        $this->loadData();
        session()->flash('success', 'Role created successfully.');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->editMode = true;
    }

    public function updateRole()
    {
        $role = Role::findOrFail($this->roleId);

        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'selectedPermissions' => 'required|array|min:1',
        ]);

        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        $this->loadData();
        session()->flash('success', 'Role updated successfully.');
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting super-admin
        if($role->name === 'super-admin'){
            session()->flash('error', 'Cannot delete super-admin role.');
            return;
        }

        $role->delete();
        $this->loadData();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.roles');
    }
}
