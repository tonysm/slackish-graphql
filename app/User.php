<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class)
            ->withTimestamps()
            ->withPivot(['role']);
    }

    public function createWorkspace(string $name): Workspace
    {
        /** @var \App\Workspace $workspace */
        $workspace = $this->workspaces()->create(['name' => $name]);

        $workspace->createDefaultChannel('general');

        return $workspace;
    }
}
