<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

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
        $id_role_admin = Uuid::uuid1();
        DB::table('roles')->insert([
            'id' => $id_role_admin,
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Tiene privilegios de lectura y escritura'
        ]);
        $id_role_user = Uuid::uuid1();
        DB::table('roles')->insert([
            'id' => $id_role_user,
            'name' => 'Usuario',
            'slug' => 'user',
            'description' => 'Tiene privilegios de lectura'
        ]);

        $id_user = Uuid::uuid1();

        DB::table('users')->insert([
            'id' => $id_user,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('1')
        ]);

        DB::table('role_user')->insert([
            'id' => Uuid::uuid1(),
            'user_id' => $id_user,
            'role_id' => $id_role_admin
        ]);

    }
}
