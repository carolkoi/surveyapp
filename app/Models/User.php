<?php

namespace App\Models;

//use Eloquent as Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package App\Models
 * @version October 25, 2019, 10:59 pm UTC
 *
 * @property string name
 * @property string email
 * @property string|\Carbon\Carbon email_verified_at
 * @property string password
 * @property string remember_token
 */
class User extends Model
{
    use SoftDeletes;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'password' => 'required'
    ];
    public function allocations(){
        return $this->hasMany(Allocation::class, 'user_id', 'id');
    }
    public function templates(){
        return $this->hasMany(Template::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name." ".$this->last_name;
    }


}
