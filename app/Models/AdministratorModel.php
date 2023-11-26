<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministratorModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_administrators';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'admin_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

        protected $fillable = ['admin_name',
        'admin_type_id',
        'username',
        'password',
        'is_active',
        'failed_logins'
    ];
}
