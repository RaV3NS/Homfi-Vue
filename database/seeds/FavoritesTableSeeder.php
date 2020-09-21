<?php

use App\Favorite;
use App\User;
use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->inRandomOrder()->limit(10)->get();

        foreach($users as $user){
            factory(Favorite::class)->create([
                'user_id'=>$user->id,
            ]);
        }
    }
}
