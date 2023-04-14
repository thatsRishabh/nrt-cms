<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitePages extends Model
{
    use HasFactory;

    public function subPages()
    {
        return $this->hasMany(SitePages::class, 'parent_id', 'id');
    }
}
