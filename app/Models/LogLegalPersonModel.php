<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogLegalPersonModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_legal_person';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
