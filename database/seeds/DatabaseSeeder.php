<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        config(['queue.default' => 'sync']);
//        \Illuminate\Support\Facades\Artisan::call('update:streets');
//        \Illuminate\Support\Facades\Artisan::call('update:subway');

//        $this->call(ParameterTableSeeder::class);
//        $this->call(AdministrativeSeeder::class);
//        $this->call(AdminUsersTableSeeder::class);
//        $this->call(UsersTableSeeder::class);
//        $this->call(AdvertsTableSeeder::class);
//        $this->call(AdvertParameterTableSeeder::class);
//        $this->call(PhonesTableSeeder::class);
//        $this->call(AdvertImagesTableSeeder::class);
//        $this->call(UserLogsTableSeeder::class);
//        $this->call(ComplainsTableSeeder::class);
//        $this->call(NotificationsTableSeeder::class);
//        $this->call(ContentTableSeeder::class);
//        $this->call(GeoTranslit::class);
    }
}
