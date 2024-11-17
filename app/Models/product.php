<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class product extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'Product_name',
    //     'description',
    //     'section_id',
    // ];
   protected $guarded = [];
   // في ملف Product.php
public function section()
{
    return $this->belongsTo(Section::class, 'section_id');
}
}