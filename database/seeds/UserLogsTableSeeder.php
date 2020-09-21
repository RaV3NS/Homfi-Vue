<?php

use App\AdminUser;
use App\User;
use App\UserLog;
use Illuminate\Database\Seeder;

class UserLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()
            ->each(function (User $user) {
                factory(UserLog::class, rand(0,3))->create([
                    'user_id' => $user->id,
                    'admin_id' => function() {
                        return AdminUser::all()->first()->id;
                    }
                ]);
            });
    }
}
