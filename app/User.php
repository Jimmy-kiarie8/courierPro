<?php

namespace App;

use App\Notifications\verifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
	use Notifiable, SoftDeletes, HasApiTokens;

	public $with = ['roles'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'verifyToken', 'status', 'company',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];


	/**
	 * The roles that belong to the user.
	 */
	public function roles() {
		return $this->belongsToMany('App\Role');
	}
	
	public function branch() {
		return $this->belongsTo('App\Branch');
	}

    public function verified()
    {
        return $this->verifyToken === null;
    }

    public function sendVerificationEmail()
    {
        $this->notify(new verifyEmail($this));
    }

}
