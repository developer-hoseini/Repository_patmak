<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefEducationGradeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefEducationGradeRepository implements RefEducationGradeRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_education_grade')->get();
    }
}