<?php


namespace Confee\Domains\Users\Database\Seeders;


use Confee\Domains\Users\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->times(10)->create();
    }
}