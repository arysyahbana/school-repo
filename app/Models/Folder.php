<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
        'color',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Pemilik folder
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Folder induk
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Sub-folder
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // File di dalam folder
    public function files()
    {
        return $this->hasMany(RepoFile::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Folder milik user login
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // Folder root (parent_id NULL)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
