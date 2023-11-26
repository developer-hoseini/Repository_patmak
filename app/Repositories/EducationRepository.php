<?php

namespace App\Repositories;

use App\Models\EducationModel;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EducationRepository implements EducationRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($appliaction_id, $data) {
        $record = new EducationModel();      
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->edu_grade_id = $data['edu_grade_id'];
        $record->edu_field_id = $data['edu_field_id'];
        $record->edu_area = $data['edu_area'];
        
        return $record->save();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Deletes all rcords correspondinf to given application_id
     */
    public function deleteAll($appliaction_id){
        $previous_records = DB::table('tbl_education_info')
            ->where('application_id', $appliaction_id)
            ->get();

        foreach($previous_records as $record){
            EducationModel::find($record->edu_id)->delete();
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_education_info AS edu')
            ->select('edu.edu_area', 'ref1.education_grade_title', 'ref2.study_field_title')
            ->where('application_id' ,$app_id)
            ->leftJoin('ref_education_grade AS ref1', 'edu_grade_id', '=', 'education_grade_id')
            ->leftJoin('ref_study_field AS ref2', 'edu_field_id', '=', 'study_field_id')
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        // This query helps to find application id of last education id.
        // As there are serveral records afor each application we need it to fetch out only records of last application.
        $raw = "(SELECT application_id FROM tbl_education_info WHERE user_id = {$user_id} ORDER BY edu_id DESC LIMIT 1)";

        return DB::table('tbl_education_info')
            ->select("edu_area","edu_field_id","edu_grade_id", 'ref1.education_grade_title', 'ref2.study_field_title')
            ->leftJoin('ref_education_grade AS ref1', 'edu_grade_id', '=', 'education_grade_id')
            ->leftJoin('ref_study_field AS ref2', 'edu_field_id', '=', 'study_field_id')
            ->where('user_id', $user_id)
            ->where('application_id', DB::raw($raw)) 
            ->get();
    }
}