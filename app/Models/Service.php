<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Menu;
class Service extends Model
{
    use HasFactory;
    
    public function menuDetail()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function subMenuDetail()
    {
        return $this->belongsTo(Menu::class, 'sub_menu_id', 'id');
    }
}
