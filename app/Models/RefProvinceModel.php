<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefProvinceModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_provinces';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'province_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
