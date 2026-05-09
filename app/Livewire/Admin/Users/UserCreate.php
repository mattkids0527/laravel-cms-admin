<?php

namespace App\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserCreate extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $status = User::STATUS_PENDING;
    public array $selectedRoles = [];

    public function save(): void
    {
        $this->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'status'                => 'required|in:pending,active,inactive',
            'selectedRoles'         => 'required|array|min:1',
            'selectedRoles.*'       => 'exists:roles,id',
        ], [
            'name.required'         => '請輸入姓名。',
            'email.required'        => '請輸入 Email。',
            'email.unique'          => '此 Email 已被使用。',
            'password.required'     => '請輸入密碼。',
            'password.min'          => '密碼至少需 8 個字元。',
            'password.confirmed'    => '兩次密碼輸入不一致。',
            'selectedRoles.required' => '請至少選擇一個角色。',
            'selectedRoles.min'     => '請至少選擇一個角色。',
        ]);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
            'status'   => $this->status,
        ]);

        $user->roles()->attach($this->selectedRoles);

        session()->flash('success', '帳號已建立成功。');
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.create', [
            'allRoles' => Role::orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => '新增帳號']);
    }
}
