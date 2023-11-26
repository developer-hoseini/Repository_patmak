<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAuthBasisModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_request_auth_basis';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'req_auth_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
