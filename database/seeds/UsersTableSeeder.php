<?php

use App\AdminUser;
use App\Advert;
use App\Phone;
use App\User;
use App\UserLog;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developers = [
            's.polyakov@dinarys.com' => 'Святослав Поляков',
            'roman@dinarys.com' => 'Роман Феско',
            'a.kozorog@dinarys.com' => 'Антон Козорог',
            'a.mudrik@dinarys.com' => 'Артур Мудрик',
        ]; //todo remove on prod
        foreach ($developers as $email => $name) {
            $name = explode(' ', $name);
            factory(User::class, 1)->create([
                'email' => $email,
                'first_name' => reset($name),
                'last_name' => end($name),
            ])->each(function (User $user) {
                factory(Advert::class, rand(1, 5))->create([
                    'user_id' => $user->id
                ]);
                factory(Phone::class, rand(1, 3))->create([
                    'model' => User::class,
                    'model_id' => $user->id
                ]);
            });
        }
        factory(User::class, 25)
            ->create()
            ->each(function (User $user) {
//                factory(Phone::class, rand(1, 3))->create([
//                    'model' => User::class,
//                    'model_id' => $user->id
//                ]);
                factory(UserLog::class, rand(0,3))->create([
                    'user_id' => $user->id,
                    'admin_id' => function() {
                        return AdminUser::all()->first()->id;
                    }
                ]);
            });
    }
}
