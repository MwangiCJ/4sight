<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('PeopleTableSeeder');
        $this->command->info('People table seeded!');
    }


}

class PeopleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('people')->delete();
        $firstnames = array('Fred', 'Joe', 'Bob', 'Jordan', 'Precious', 'Lindi', 'Samuel');
        $surnames = array('Brown', 'Smith', 'Mavundla', 'Bloggs', 'Dlamini', 'Jackson', 'Fredericks');
        $hospitalnumbers = array('12345432', '4546532', '3426562', '3246', '348264', '7469301', '38523');
        $grades = array('1', '4', NULL, '3', '2', '1', '1');
        $date_booked = array('21 June 2012', '23 July 2012', '3 May 2012', '29 December 2012', '23 April 2013',
            '16 June 2015', '2 Feb 2014');
        $surgery_types = array('1', '1', '3', '2','6','1','2');
        foreach ($firstnames as $key=>$name) {
            Person::create(array('first_name' => $firstnames[$key], 'surname' => $surnames[$key],
                'hospital_number' => $hospitalnumbers[$key], 'grade' => $grades[$key],
                'date_booked' => $date_booked[$key]));
        }

        DB::table('appointmenttypes')->delete();
        Appointmenttype::create(array('name' => 'Pre-op'));
        Appointmenttype::create(array('name' => 'Post-op'));

        DB::table('surgerytypes')->delete();
        Surgerytype::create(array('name'=>'P+I', "group"=>1));
        Surgerytype::create(array('name'=>'E+I', "group"=>1));
        Surgerytype::create(array('name'=>'Secondary IOL', "group"=> 1));
        Surgerytype::create(array('name'=>'Other Intra-ocular Procedures', "group"=>null));
        Surgerytype::create(array('name'=>'Strab', "group"=>null));
        Surgerytype::create(array('name'=>'Trab' , "group"=>null));
        Surgerytype::create(array('name'=>'Conj Mass Excision', "group"=>2));
        Surgerytype::create(array('name'=>'Other Extra-ocular Procedures', "group"=>2));

        $i = 0;
        foreach ($surgery_types as $s) {
            $i++;
            Surgery::create(array('person_id' => $i, 'surgerytype_id' => $s));
        }


    }

}
