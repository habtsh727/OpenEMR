<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Users extends Component
{
    public $users;
    public $roles;
    public $name, $email, $password, $userId, $is_active;
    public $selectedRoles = [];
    public $editMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'nullable|min:6',
        'selectedRoles' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::with('roles')->get();
        $this->roles = Role::all();
    }

    public function resetForm()
    {
        $this->reset(['name','email','password','userId','selectedRoles','is_active','editMode']);
    }

    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password ?? 'password'),
            'is_active' => $this->is_active ?? true,
        ]);

        $user->syncRoles($this->selectedRoles);

        $this->resetForm();
        $this->loadData();
        session()->flash('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->editMode = true;
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->userId);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email',Rule::unique('users','email')->ignore($user->id)],
            'selectedRoles' => 'required|array|min:1',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active ?? false,
        ]);

        if($this->password){
            $user->update(['password' => Hash::make($this->password)]);
        }

        $user->syncRoles($this->selectedRoles);

        $this->resetForm();
        $this->loadData();
        session()->flash('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->loadData();
        session()->flash('success', 'User deleted successfully.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $this->loadData();
        session()->flash('success', 'User status updated.');
    }

    public function render()
    {
        return view('livewire.admin.users');
    }
}

