<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(AdminTableSeeder::class);
    }
    
}
class RoleTableSeeder extends Seeder
    {
    	public function run()
    	{
    		DB::table('roles')->delete();
    		DB::table('roles')->insert([
    			'id' => '1',
    			'roles' => 'Root Admin',
			]);
    		DB::table('roles')->insert([
    			'id' => '2',
    			'roles' => 'Admin',
			]);
    		DB::table('roles')->insert([
    			'id' => '3',
    			'roles' => 'Mod',
			]);
    	}
    }
class AdminTableSeeder extends Seeder
    {
    	public function run()
    	{
    		DB::table('admins')->delete();
    		DB::table('admins')->insert([
    			'id' => '1',
    			'name' => 'Huu Vien',
    			'username' => 'huuvien17',
    			'email' => 'huuvien17@gmail.com',
    			'password' => bcrypt('vien5048'),
    			'role_id' => '1',
			]);
    	}
    }
