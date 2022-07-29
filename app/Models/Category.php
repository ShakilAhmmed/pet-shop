<?php

namespace App\Models;

use App\Traits\SortAble;
use App\Traits\UUIDAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use UUIDAble;
    use SortAble;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];
}
