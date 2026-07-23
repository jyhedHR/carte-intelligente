<?php
// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Notification extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $table = 'wfb_notifications';

    protected $fillable = [
        'id_user',
        'heure',
        'message',
        'canal',
        'type',
        'lu',
        'envoye_a',
        'expires_at',
        'process_instance_id',
        'task_id',
        'admin_comment',
        'reference'
    ];

    protected $casts = [
        'heure'      => 'datetime',
        'envoye_a'   => 'datetime',
        'expires_at' => 'datetime',
        'lu'         => 'boolean',
    ];

    // Prunable: delete READ notifications older than 30 days
    public function prunable(): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('lu', true)
            ->where('updated_at', '<=', now()->subDays(30));
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('lu', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
