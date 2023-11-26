<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefStudyFieldRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefStudyFieldRepository implements RefStudyFieldRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_study_field')->get();
    }

    public function getByGrade($education_grade_id)
    {
        return DB::table('ref_study_field')->where('education_grade_id', $education_grade_id)->get();
    }

    public function find($ref_study_field)
    {
        return DB::table('ref_study_field')->where('study_field_id', $ref_study_field)->first();
    }
}