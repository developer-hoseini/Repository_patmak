<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogOtpModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_otp';

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

    protected $fillable = ['mobile', 'otp_code', 'expires_at', 'tries', 'created_at', 'updated_at'];
}
