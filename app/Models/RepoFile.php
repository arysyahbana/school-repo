<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepoFile extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'original_name',
        'folder_id',
        'user_id',
        'sender_id',
        'send_batch_id',
        'document_date',
        'storage_type',
        'path',
        'drive_url',
        'mime_type',
        'size',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Pemilik file
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Folder tempat file berada
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // File milik user login
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // File di root (folder_id NULL)
    public function scopeRoot($query)
    {
        return $query->whereNull('folder_id');
    }

    // File lokal saja
    public function scopeLocal($query)
    {
        return $query->where('storage_type', 'local');
    }

    // File Google Drive saja
    public function scopeGdrive($query)
    {
        return $query->where('storage_type', 'gdrive');
    }
}
