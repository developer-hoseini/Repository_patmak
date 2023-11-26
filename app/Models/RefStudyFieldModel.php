<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefStudyFieldModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_study_field';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'study_field_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
