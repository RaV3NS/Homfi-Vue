<?php

use App\AdminUser;
use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seed.
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
        ]; //todo remove in production
        foreach ($developers as $email => $name) {
            factory(AdminUser::class, 1)->create([
                'email' => $email,
                'name' => $name,
            ]);
        }
    }
}
