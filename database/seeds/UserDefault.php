<?php

use Illuminate\Database\Seeder;
use Hash;
use Spatie\Permission\Models\Role;

class UserDefault extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(
            array('name' => 'superadmin', 'guard_name' => "web"),
            array('name' => 'admin', 'guard_name' => "web"),
            array('name' => 'user', 'guard_name' => "web"),
        );
        DB::table('roles')->insert($data); // Query Builder approach
        $cmp_id = \App\Cmp::firstOrCreate(array("name" => "localhost"))->id;
        $data = array(
            array('name' => 'admin', 'email' => "admin@a.com", 'password'=> Hash::make("admin#123"),'cmp_id'=> $cmp_id),
        );
        $user = DB::table('users')->insert($data); // Query Builder approach
        $user->assignRole("superadmin");
        
    }
}
