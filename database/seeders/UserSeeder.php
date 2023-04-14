<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         //
        /*------------Default Role-----------------------------------*/
        $role1 = Role::create([
            'id' => '1',
            'name' => 'Admin',
            'guard_name' => 'api'
        ]);
        $role2 = Role::create([
            'id' => '2',
            'name' => 'AdminEmployee',
            'guard_name' => 'api'
        ]);
       
        /*-----------Create Admin-------------*/
        $adminUser = new User();
        $adminUser->id                      = '1';
        $adminUser->role_id                 = '1';
        $adminUser->name                    = 'Admin';
        $adminUser->email                   = 'admin@gmail.com';
        $adminUser->password                = \Hash::make(12345678);
        $adminUser->save();

        $adminRole = Role::where('id','1')->first();
        $adminUser->assignRole($adminRole);
        
      
       /*-----------Create AdminEmployee-------------*/
        $adminEmployeeUser = new User();
        $adminEmployeeUser->id                      = '2';
        $adminEmployeeUser->role_id                 = '2';
       $adminEmployeeUser->name                    = 'AdminEmployee';
       $adminEmployeeUser->email                   = 'adminemployee@gmail.com';
       $adminEmployeeUser->password                = \Hash::make(12345678);
       $adminEmployeeUser->save();

        $adminEmployeeRole = Role::where('id','5')->first();
        $adminEmployeeUser->assignRole($adminEmployeeRole);

    }
}
