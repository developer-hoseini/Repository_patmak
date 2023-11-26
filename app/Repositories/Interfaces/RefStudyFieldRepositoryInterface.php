<?php

namespace App\Repositories\Interfaces;

Interface RefStudyFieldRepositoryInterface {

    public function getAll();

    public function getByGrade($education_grade_id);

    public function find($stydy_field_id);
}