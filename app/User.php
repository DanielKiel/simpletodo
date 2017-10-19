<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_superadmin', 'tenants_id', 'settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_superadmin' => 'boolean',
        'settings' => 'object'
    ];

    public function isSuperAdmin()
    {
        return $this->is_superadmin;
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'by');
    }

    /**
     * @param null $setting
     * @param null $default
     * @return mixed|null
     */
    public function profile($setting = null, $default = null)
    {
        if (is_string($setting) && $setting !== '') {
            if (! is_object($this->settings)) {
                return $default;
            }

            if (property_exists($this->settings, $setting)) {
                return $this->settings->{$setting};
            }

            return $default;
        }

        return $this->settings;
    }
}
