<?php

use App\AdminUser;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminUserRequest;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        AdminUser::create([
            'name'=>'Pyae Phyoe Naing',
            'email'=>'pyaephyoenaing@gmail.com',
            'phone'=>'09777758089',
            'password'=>Hash::make('password'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
