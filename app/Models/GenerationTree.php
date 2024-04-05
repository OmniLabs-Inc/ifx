<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\{User, UserMeta};

class GenerationTree extends Model
{
    use HasFactory, NodeTrait;

    protected $table = "generation_tree";

    // protected $id

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usermeta()
    {
        return $this->belongsTo(UserMeta::class, "user_id" , "user_id");
    }
}
