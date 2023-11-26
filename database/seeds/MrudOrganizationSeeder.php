<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MrudOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_mrud_organizations')->insert(
            ['morg_id' => 1, 'morg_title' => 'نظام مهندسی ساختمان'],
            ['morg_id' => 2, 'morg_title' => 'نظام کاردانی ساختمان']
        );
    }
}
