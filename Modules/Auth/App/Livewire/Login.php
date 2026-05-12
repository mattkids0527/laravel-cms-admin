<?php

namespace Modules\Auth\App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login(): void
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => '請輸入 Email。',
            'email.email'       => 'Email 格式不正確。',
            'password.required' => '請輸入密碼。',
        ]);

        if (! Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Email 或密碼不正確。');
            return;
        }

        $user = Auth::guard('admin')->user();

        if (! $user->isActive()) {
            Auth::guard('admin')->logout();
            $statusLabel = match ($user->status) {
                'pending'  => '待審核',
                'inactive' => '已停用',
                default    => '無法登入',
            };
            $this->addError('email', "此帳號目前狀態為「{$statusLabel}」，無法登入。");
            return;
        }

        $user->update(['last_login_at' => now()]);

        session()->regenerate();
        $this->redirect(route('admin.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('auth::livewire.login')
            ->layout('components.layouts.guest');
    }
}
