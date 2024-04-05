<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultTemplate extends Model
{
    protected $fillable = [
        "type",
        "temp_type",
        "content",
    ];

    protected $hidden = [
        "extra"
    ];

    // *************************************************//
    //              Getter And Setters                  //
    // *************************************************//

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    public function setTempTypeAttribute($value)
    {
        $this->attributes['temp_type'] = strtolower($value);
    }


    public function setContentAttribute($value)
    {
        $this->attributes['content'] =  json_encode($value);
    }

    public function getContentAttribute($value)
    {
        return json_decode($value,true);
    }

    // *************************************************//
    //                  Relationships                   //
    // *************************************************//

    # public function FunctionName()
    # {
    #     return $this->hasOne(ModelName::class);
    # }

    // *************************************************//
    //              Additional Keys                     //
    // *************************************************//

    # public function getDesignationViewAttribute()
    # {
    #     $data = Designation::Find($this->designation_id);
    #     return !$data ? "" : $data['title'] ;
    # }


    // *************************************************//
    //              To Array Function                   //
    // *************************************************//

    # public function toArray()
    # {
    #     $array = parent::toArray();
    #     $array['designation_view'] = $this->{'designation_view'};
    #     return $array;
    # }

    # public function toArray()
    # {
    #         $array = parent::toArray();
    #         foreach ($this->getMutatedAttributes() as $key)
    #         {
    #             if ( ! array_key_exists($key, $array)) {
    #                 $array[$key] = $this->{$key};
    #             }
    #         }
    #         return $array;
    # }
}
