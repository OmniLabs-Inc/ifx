<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\{User, UserMeta, StakedPlan};

class BinaryTree extends Model
{
    use HasFactory, NodeTrait;

    protected $table = "binary_tree";

    // protected $id

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'user_id',
        'side',
        'team'
    ];


    // public function getKeyAttribute()
    // {
    //     return $this->id;
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usermeta()
    {
        return $this->belongsTo(UserMeta::class, "user_id" , "user_id");
    }

    public function staked_plan()
    {
        return $this->belongsTo(StakedPlan::class, "user_id" , "user_id");
    }

    


    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     foreach ($this->getMutatedAttributes() as $key) {
    //         if (!array_key_exists($key, $array)) {
    //             $array[$key] = $this->{$key};
    //         }
    //     }
    //     return $array;
    // }

}
