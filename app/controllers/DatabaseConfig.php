<?php
class DatabaseConfig extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	
	
	static public function checkDb() {






			if (!Schema::hasTable('Licence')) {

			log::info( '------Licence not avaliable---------- ');
				Schema::create('Licence', function ($table) {
				$table->increments('licence_id')->first();
				$table->text('type')->unique()->after('licence_id');
				$table->text('status')->nullable()->after('type');
				$table->dateTime('create_time_stamp')->after('status');
				$table->date('create_date')->nullable()->after('create_time_stamp');
			   // $table->primary('licence_id');
			});
			    //
			}




			if (!Schema::hasTable('Payment_Mode')) {

			log::info( '------Payment_Mode not avaliable---------- ');
				Schema::create('Payment_Mode', function ($table) {
				$table->increments('payment_mode_id')->first();
				$table->text('type')->unique()->after('payment_mode_id');
				$table->text('status')->nullable()->after('type');
				$table->dateTime('create_time_stamp')->after('status');
				$table->date('create_date')->nullable()->after('create_time_stamp');
			    //$table->primary('payment_mode_id');
			});
			    //
			}


			if (!Schema::hasTable('Vehicle_details')) {

			log::info( '------Vehicle_details not avaliable---------- ');
				Schema::create('Vehicle_details', function ($table) {
				$table->text('vehicle_id')->first();
				$table->text('fcode')->after('vehicle_id');
				$table->date('sold_date')->after('fcode');
				$table->integer('sold_time_stamp')->after('sold_date');
				$table->integer('month')->after('sold_time_stamp');
				$table->integer('year')->after('month');
				$table->text('status')->nullable()->after('year');
				$table->text('belongs_to')->after('status');
				$table->text('device_id')->after('belongs_to');
				$table->date('renewal_date')->after('device_id');
				$table->integer('payment_mode_id');
				//$table->foreign('payment_mode_id')->references('payment_mode_id')->on('Payment_Mode');
				$table->integer('licence_id');
				//$table->foreign('licence_id')->references('licence_id')->on('Licence');
			    $table->primary(['vehicle_id','fcode']);
			});
			    //
			}
				

	}
		}

