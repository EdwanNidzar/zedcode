<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ApprovalChainConfig;
use App\Services\ApprovalChainService;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ApprovalChainManager extends Component
{
    public int $activeLevel = 2; // Default tab: Employee

    // Form tambah step baru
    public string $newRole    = '';
    public int    $newOrder   = 0;
    public bool   $isAdding   = false;

    public function mount(): void
    {
        $this->activeLevel = 2;
    }

    /** Daftar tab level yang bisa dikonfigurasi */
    public function getLevelsProperty(): array
    {
        return [
            1 => 'Intern',
            2 => 'Employee',
            3 => 'SPV',
            4 => 'HR / Manager',
            5 => 'Manager / GM',
            6 => 'Direktur',
        ];
    }

    /** Config aktif untuk level yang sedang ditampilkan */
    public function getConfigsProperty()
    {
        return ApprovalChainConfig::where('requester_level', $this->activeLevel)
            ->orderBy('step_order')
            ->get();
    }

    /** Role yang tersedia untuk ditambahkan (hanya lebih tinggi dari activeLevel) */
    public function getAvailableRolesProperty(): array
    {
        $levelMap = ApprovalChainService::ROLE_LEVELS;

        // Sudah dipakai untuk level ini
        $used = ApprovalChainConfig::where('requester_level', $this->activeLevel)
            ->pluck('approver_role')
            ->toArray();

        return collect($levelMap)
            ->filter(fn($lvl, $role) =>
                $lvl > $this->activeLevel &&
                $role !== 'Super Admin' &&
                !in_array($role, $used)
            )
            ->sortBy(fn($lvl) => $lvl)
            ->keys()
            ->toArray();
    }

    public function setTab(int $level): void
    {
        $this->activeLevel = $level;
        $this->isAdding    = false;
        $this->newRole     = '';
        $this->resetValidation();
    }

    public function toggleActive(int $configId): void
    {
        $config = ApprovalChainConfig::findOrFail($configId);
        $config->update(['is_active' => !$config->is_active]);
    }

    public function moveUp(int $configId): void
    {
        $current = ApprovalChainConfig::findOrFail($configId);
        $prev = ApprovalChainConfig::where('requester_level', $this->activeLevel)
            ->where('step_order', '<', $current->step_order)
            ->orderByDesc('step_order')
            ->first();

        if ($prev) {
            [$current->step_order, $prev->step_order] = [$prev->step_order, $current->step_order];
            $current->save();
            $prev->save();
        }
    }

    public function moveDown(int $configId): void
    {
        $current = ApprovalChainConfig::findOrFail($configId);
        $next = ApprovalChainConfig::where('requester_level', $this->activeLevel)
            ->where('step_order', '>', $current->step_order)
            ->orderBy('step_order')
            ->first();

        if ($next) {
            [$current->step_order, $next->step_order] = [$next->step_order, $current->step_order];
            $current->save();
            $next->save();
        }
    }

    public function deleteStep(int $configId): void
    {
        ApprovalChainConfig::findOrFail($configId)->delete();
        session()->flash('success', 'Step berhasil dihapus.');
    }

    public function openAdd(): void
    {
        $this->isAdding = true;
        $this->newRole  = '';
        // Default order = max + 1
        $max = ApprovalChainConfig::where('requester_level', $this->activeLevel)->max('step_order') ?? 0;
        $this->newOrder = $max + 1;
    }

    public function addStep(): void
    {
        $this->validate([
            'newRole'  => 'required|string',
            'newOrder' => 'required|integer|min:1',
        ]);

        $levelMap      = ApprovalChainService::ROLE_LEVELS;
        $approverLevel = $levelMap[$this->newRole] ?? 0;

        if ($approverLevel <= $this->activeLevel) {
            $this->addError('newRole', 'Role approver harus memiliki level lebih tinggi dari pemohon.');
            return;
        }

        // Geser step_order yang bentrok
        ApprovalChainConfig::where('requester_level', $this->activeLevel)
            ->where('step_order', '>=', $this->newOrder)
            ->increment('step_order');

        ApprovalChainConfig::create([
            'requester_level' => $this->activeLevel,
            'approver_role'   => $this->newRole,
            'approver_level'  => $approverLevel,
            'step_order'      => $this->newOrder,
            'is_active'       => true,
            'created_by'      => Auth::id(),
        ]);

        $this->isAdding = false;
        $this->newRole  = '';
        session()->flash('success', "Step '{$this->newRole}' berhasil ditambahkan.");
    }

    public function resetToDefault(): void
    {
        $levelMap = ApprovalChainService::ROLE_LEVELS;

        $defaults = [
            1 => [['SPV', 3, 1], ['HR / Manager', 4, 2], ['Manager / GM', 5, 3], ['Direktur', 6, 4]],
            2 => [['SPV', 3, 1], ['HR / Manager', 4, 2], ['Manager / GM', 5, 3], ['Direktur', 6, 4]],
            3 => [['HR / Manager', 4, 1], ['Manager / GM', 5, 2], ['Direktur', 6, 3]],
            4 => [['Manager / GM', 5, 1], ['Direktur', 6, 2]],
            5 => [['Direktur', 6, 1], ['CEO', 7, 2]],
            6 => [['CEO', 7, 1]],
        ];

        ApprovalChainConfig::where('requester_level', $this->activeLevel)->delete();

        foreach (($defaults[$this->activeLevel] ?? []) as [$role, $level, $order]) {
            ApprovalChainConfig::create([
                'requester_level' => $this->activeLevel,
                'approver_role'   => $role,
                'approver_level'  => $level,
                'step_order'      => $order,
                'is_active'       => true,
                'created_by'      => Auth::id(),
            ]);
        }

        session()->flash('success', 'Chain berhasil direset ke default.');
    }

    public function render()
    {
        return view('livewire.admin.approval-chain-manager', [
            'levels'         => $this->levels,
            'configs'        => $this->configs,
            'availableRoles' => $this->availableRoles,
        ])->layout('components.app-layout', ['title' => 'Rantai Approval']);
    }
}
