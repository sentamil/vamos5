<?php

class UserTableSeeder extends Seeder
{

	public function run()
	{
		//DB::table('users')->delete();
		User::create(array(
			'name'     => 'Shivaji Motors',
			'username' => 'santhoshi',
			'email'    => 'test@gmail.com',
			'password' => Hash::make('awesome'),
		));
		User::create(array(
		'name'     => 'VAMOSYS',
		'username' => 'vamos',
		'email'    => 'prkothan@gmail.com',
		'password' => Hash::make('awesome'),
		));
		User::create(array(
		'name'     => 'DEMO',
		'username' => 'demouser1',
		'email'    => 'prkothan@gmail.com',
		'password' => Hash::make('awesome'),
		));
	        User::create(array(
                'name'     => 'DEMO',
                'username' => 'alhadeed',
                'email'    => 'prkothan@gmail.com',
                'password' => Hash::make('awesome'),
                ));
	
	}

}

