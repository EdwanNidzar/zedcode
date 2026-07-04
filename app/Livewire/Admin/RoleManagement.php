<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleManagement extends Component
{
    use WithPagination, AuthorizesRequests;

    public $searchRole = '';
    public $searchPermission = '';
    
    public $isRoleModalOpen = false;
    public $isPermissionModalOpen = false;

    // Role Form fields
    public $roleId;
    public $name = '';
    public $selectedPermissions = [];

    // Permission Form fields
    public $permissionId;
    public $permissionName = '';

    // All available permissions
    public $allPermissions = [];
    
    // Feature to add new permission on the fly in role modal
    public $newPermissionName = '';

    public function mount()
    {
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $this->allPermissions = Permission::orderBy('name')->get();
    }

    public function updatingSearchRole()
    {
        $this->resetPage();
    }
    
    public function updatingSearchPermission()
    {
        $this->resetPage();
    }

    // --- ROLE MANAGEMENT ---

    public function openRoleModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'selectedPermissions', 'roleId', 'newPermissionName']);
        $this->loadPermissions();

        if ($id) {
            $role = Role::findById($id);
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }

        $this->isRoleModalOpen = true;
    }

    public function closeRoleModal()
    {
        $this->isRoleModalOpen = false;
    }

    public function createPermissionOnTheFly()
    {
        $this->validate([
            'newPermissionName' => 'required|string|unique:permissions,name'
        ]);

        Permission::create(['name' => $this->newPermissionName]);
        
        $this->selectedPermissions[] = $this->newPermissionName;
        $this->newPermissionName = '';
        $this->loadPermissions();
        
        session()->flash('permission_success', 'Permission baru berhasil ditambahkan.');
    }

    public function saveRole()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'nullable|array'
        ]);

        if ($this->roleId) {
            $role = Role::findById($this->roleId);
            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create(['name' => $this->name]);
        }

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success_role', $this->roleId ? 'Role berhasil diperbarui.' : 'Role berhasil ditambahkan.');
        $this->closeRoleModal();
    }

    public function deleteRole($id)
    {
        // Pastikan hanya Super Admin (atau yang diizinkan lewat Gate) yang bisa hapus
        $this->authorize('delete role');

        $role = Role::findById($id);
        
        if ($role->name === 'Super Admin') {
            session()->flash('error_role', 'Role Super Admin tidak dapat dihapus.');
            return;
        }

        $role->delete();
        session()->flash('success_role', 'Role berhasil dihapus.');
    }

    // --- PERMISSION MANAGEMENT ---

    public function openPermissionModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['permissionName', 'permissionId']);

        if ($id) {
            $permission = Permission::findById($id);
            $this->permissionId = $permission->id;
            $this->permissionName = $permission->name;
        }

        $this->isPermissionModalOpen = true;
    }

    public function closePermissionModal()
    {
        $this->isPermissionModalOpen = false;
    }

    public function savePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|unique:permissions,name,' . $this->permissionId,
        ]);

        if ($this->permissionId) {
            $permission = Permission::findById($this->permissionId);
            $permission->update(['name' => $this->permissionName]);
        } else {
            Permission::create(['name' => $this->permissionName]);
        }

        $this->loadPermissions(); // update the list
        session()->flash('success_permission', $this->permissionId ? 'Permission berhasil diperbarui.' : 'Permission berhasil ditambahkan.');
        $this->closePermissionModal();
    }

    public function deletePermission($id)
    {
        $this->authorize('delete permission');

        $permission = Permission::findById($id);
        $permission->delete();
        
        $this->loadPermissions();
        session()->flash('success_permission', 'Permission berhasil dihapus.');
    }

    // --- RENDER ---

    public function render()
    {
        $roles = Role::with('permissions')
            ->where('name', 'like', '%' . $this->searchRole . '%')
            ->orderBy('name')
            ->paginate(5, ['*'], 'rolePage');

        $permissionsList = Permission::with('roles')
            ->where('name', 'like', '%' . $this->searchPermission . '%')
            ->orderBy('name')
            ->paginate(5, ['*'], 'permPage');

        return view('livewire.admin.role-management', compact('roles', 'permissionsList'))
            ->layout('components.app-layout');
    }
}
