<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewModel extends Model
{
    protected $connection ='mysql';
    protected $table ='user';
}
