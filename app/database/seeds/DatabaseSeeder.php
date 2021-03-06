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

        /* -------------------- Set up necessary structure ------------------------- */
        DB::table('surgerydataneeded')->delete();
        DB::table('surgerydata')->delete();
        DB::table('surgerydatatypes')->delete();
        DB::table('surgerytypes')->delete();
        DB::table('surgeries')->delete();
        DB::table('people')->delete();


        Appointmenttype::create(array('name' => 'Pre-op'));
        Appointmenttype::create(array('name' => 'Post-op'));


        Surgerydatatype::create(array('name' => 'pre_op_va', 'label' => 'Pre-op VA', 'post_surgery'=>false));
        Surgerydatatype::create(array('name' => 'post_op_va', 'label' => 'Post-op VA', 'post_surgery'=>true));
        Surgerydatatype::create(array('name' => 'biometry', 'label' => 'Biometry', 'post_surgery'=>false));
        Surgerydatatype::create(array('name' => 'histological_outcome',
            'label' => 'Histological outcome', 'post_surgery'=>true));
//	    Surgerydatatype::create(array('name' => 'seniority', 'label' => 'Seniority', 'post_surgery'=>false));

        Surgerytype::create(array('name'=>'P+I'));
        Surgerytype::create(array('name'=>'E+I'));
        Surgerytype::create(array('name'=>'Secondary IOL'));
        Surgerytype::create(array('name'=>'Other Intra-ocular Procedures'));
        Surgerytype::create(array('name'=>'Strab'));
        Surgerytype::create(array('name'=>'Trab'));
        Surgerytype::create(array('name'=>'Conj Mass Excision'));
        Surgerytype::create(array('name'=>'Other Extra-ocular Procedures'));

        // add VA and biometry to P+I, E+I and secondary IOL
        $surgeryTypes = Surgerytype::whereIn('name', array('P+I', 'E+I', 'Secondary IOL'))->get();
        foreach ($surgeryTypes as $surgeryType) {
            $surgeryType->surgerydatatypes()->sync(array(1,2,3));
        }

        //add hist outcome to Conj.
        $surgeryType = Surgerytype::where('name', '=',  'Conj Mass Excision')->first();
        $surgeryType->surgerydatatypes()->sync(array(4));

        //add hist and VA to Other Extra-ocular Procedures
        $surgeryType = Surgerytype::where('name', '=',  'Other Extra-ocular Procedures')->first();
        $surgeryType->surgerydatatypes()->sync(array(1,2,4));


	    /* --------------------- Add options for surgerydatatypes --------- */
	    $possibleVAs = array('6/5', '6/6', '6/12', '6/18', '6/36', '6/60', '6/120', 'CF', 'HM', 'LP', 'NLP');

	    $surgerydatatype_id = SurgeryDataType::where('name', '=',  'pre_op_va')->first()->id;
	    foreach ($possibleVAs as $key => $VAValue) {
		    SurgeryDataTypeOption::create(array(
				    'surgerydatatype_id'=>$surgerydatatype_id,
				    'value'=>$VAValue,
				    'listorder' => $key)
		    );
	    }

	    $surgerydatatype_id = SurgeryDataType::where('name', '=',  'post_op_va')->first()->id;
	    foreach ($possibleVAs as $key => $VAValue) {
		    SurgeryDataTypeOption::create(array(
				    'surgerydatatype_id'=>$surgerydatatype_id,
				    'value'=>$VAValue,
				    'listorder' => $key)
		    );
	    }


	    // add seniority to 1st 4 types
//	    $surgeryType = Surgerytype::whereIn('name', array('P+I', 'E+I', 'Secondary IOL', 'Other Intra-ocular Procedures'))->get();
//	    $surgeryType->surgerydatatypes()->sync(array(5));


        /* -------------------- Add seed data ------------------------- */
        // seed data
        $firstnames = array('Fred', 'Joe', 'Bob', 'Jordan', 'Precious', 'Lindi', 'Samuel','Loyiso','Tony','George');
        $surnames = array('Brown', 'Smith', 'Mavundla', 'Bloggs', 'Dlamini', 'Jackson', 'Fredericks','Dandala','Stark','Lucas');
        $hospitalnumbers = array('12345432', '4546532', '3426562', '3246', '348264', '7469301', '38523','12334','76376','1445');
        $grades = array('1', '4', null, '3', '2', '1', '1',null,'2','2');
        $date_booked = array('21 June 2012', '23 July 2012', '3 May 2012', '29 December 2012', '23 April 2013',
            '16 June 2011', '2 Feb 2010', '1 Sep 2010', '13 Dec 2010','3 Mar 2013');
        $surgery_types = array('1', '1', '3', '2','6','1','2','2','5','4');
        $dates = array('1 June 2011', '21 Aug 2011', null, '13 Feb 2013',null,'3 March 2010','28 June 2013',
            '6 Aug 2013','11 Jul 2013','28 June 2013');

        // add seed people
        foreach ($firstnames as $key=>$name) {
            Person::create(array('first_name' => $firstnames[$key], 'surname' => $surnames[$key],
                'hospital_number' => $hospitalnumbers[$key], 'grade' => $grades[$key],
                'date_booked' => $date_booked[$key]));
        }

        //add seed surgeries
        $i = 0;
        foreach ($surgery_types as $s) {
            $i++;
            Surgery::create(array('person_id' => $i, 'surgerytype_id' => $s, 'date' => $dates[$i-1]));
        }

    }

}
