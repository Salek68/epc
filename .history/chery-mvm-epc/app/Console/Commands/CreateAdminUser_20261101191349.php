<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create the main admin account';

    public function handle()
    {
        $name = $this->ask('نام مدیر');
        $email = $this->ask('ایمیل مدیر');
        $password = $this->secret('رمز عبور مدیر');

        if (User::where('email', $email)->exists()) {
            $this->error('این ایمیل قبلاً ثبت شده است.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info('حساب مدیر با موفقیت ساخته شد.');

        return self::SUCCESS;
    }
}
