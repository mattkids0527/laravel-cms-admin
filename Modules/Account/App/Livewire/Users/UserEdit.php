<?php

namespace Modules\Account\App\Livewire\Users;

use Livewire\Component;
use Modules\Account\App\Models\Role;
use Modules\Account\App\Models\User;

class UserEdit extends Component
{
    public User $user;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $status = '';
    public array $selectedRoles = [];

    public function mount(User $user): void
    {
        $this->user          = $user;
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->status        = $user->status;
        $this->selectedRoles = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }

    public function save(): void
    {
        $rules = [
            'name'            => 'required|string|max:100',
            'email'           => "required|email|unique:users,email,{$this->user->id}",
            'status'          => 'required|in:pending,active,inactive',
            'selectedRoles'   => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
        ];

        if ($this->password !== '') {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $this->validate($rules, [
            'name.required'          => '請輸入姓名。',
            'email.required'         => '請輸入 Email。',
            'email.unique'           => '此 Email 已被使用。',
            'password.min'           => '密碼至少需 8 個字元。',
            'password.confirmed'     => '兩次密碼輸入不一致。',
            'selectedRoles.required' => '請至少選擇一個角色。',
        ]);

        if ($this->user->id === auth()->id()) {
            $this->selectedRoles = $this->user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        }

        if ($this->user->isSuperAdmin() && $this->status !== User::STATUS_ACTIVE) {
            $otherActiveSupers = User::where('status', User::STATUS_ACTIVE)
                ->whereHas('roles', fn ($q) => $q->where('is_protected', true))
                ->where('id', '!=', $this->user->id)
                ->count();

            if ($otherActiveSupers === 0) {
                $this->addError('status', '系統中必須至少保留一個啟用的超級管理員，無法變更此帳號狀態。');
                return;
            }
        }

        $data = [
            'name'   => $this->name,
            'email'  => $this->email,
            'status' => $this->status,
        ];

        if ($this->password !== '') {
            $data['password'] = $this->password;
        }

        $this->user->update($data);
        $this->user->roles()->sync($this->selectedRoles);

        session()->flash('success', '帳號已更新成功。');
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('account::livewire.users.edit', [
            'allRoles' => Role::orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => '編輯帳號']);
    }
}
