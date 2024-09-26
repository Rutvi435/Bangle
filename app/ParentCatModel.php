<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentCatModel extends Model
{
  protected $table ='tbl_categories_parent';
  protected $hidden = ['created_at', 'updated_at'];

    public function map()
    {
      return $this->hasMany(CategoryMapModel::class, 'parent_id', 'id')->with('parent:id,name','category:id,name')->select(['category_id','parent_id']);
    }

}
