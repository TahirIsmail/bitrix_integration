<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
class Courses extends Model
{
    protected $table = 'tbl_courses';

    /**
     * @return BelongsTo
     */

    public function user()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

}
