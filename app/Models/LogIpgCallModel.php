<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogIpgCallModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_ipg_call';

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
    public $timestamps = true;
}
