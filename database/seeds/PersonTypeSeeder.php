<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_mrud_organizations')->insert(
            ['person_type_id' => 1, 'person_type_title' => 'حقیقی'],
            ['person_type_id' => 2, 'person_type_title' => 'حقوقی']
        );
    }
}
