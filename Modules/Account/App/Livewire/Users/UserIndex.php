<?php

namespace Modules\Account\App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Account\App\Models\User;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $confirmingDeleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDeleteId = $userId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function delete(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->isSuperAdmin() && User::where('status', User::STATUS_ACTIVE)->whereHas('roles', fn ($q) => $q->where('is_protected', true))->count() <= 1) {
            session()->flash('error', '系統中必須至少保留一個啟用的超級管理員。');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($user->id === auth()->id()) {
            session()->flash('error', '不可刪除自己的帳號。');
            $this->confirmingDeleteId = null;
            return;
        }

        $user->delete();
        $this->confirmingDeleteId = null;
        session()->flash('success', '帳號已刪除。');
    }

    public function toggleStatus(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            session()->flash('error', '不可變更自己的帳號狀態。');
            return;
        }

        if ($user->isActive() && $user->isSuperAdmin()) {
            $activeSupers = User::where('status', User::STATUS_ACTIVE)
                ->whereHas('roles', fn ($q) => $q->where('is_protected', true))
                ->count();
            if ($activeSupers <= 1) {
                session()->flash('error', '系統中必須至少保留一個啟用的超級管理員。');
                return;
            }
        }

        $user->status = $user->isActive() ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $user->save();

        session()->flash('success', '帳號狀態已更新。');
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->search}%")
                  ->orWhere('email', 'ilike', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('account::livewire.users.index', compact('users'))
            ->layout('components.layouts.admin', ['title' => '帳號管理']);
    }
}
