<?php

namespace audunru\ExportResponse\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'description',
        'gross_cost',
    ];
}
