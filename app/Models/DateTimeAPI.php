<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateTimeAPI extends Model
{
    protected $connection = 'mysql';
    protected $table = "date_time_records";
}
