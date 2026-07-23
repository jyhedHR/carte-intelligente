<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSession extends Model
{
    use HasFactory;

    protected $table = 'wfb_user_sessions';

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_name',
        'last_activity',
        'logged_in_at',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'logged_in_at' => 'datetime',
    ];

    /**
     * Get the user that owns this session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if session is still active (within last 24 hours)
     */
    public function isActive(): bool
    {
        return $this->last_activity && $this->last_activity->diffInHours(now()) < 24;
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowserAttribute()
    {
        $userAgent = $this->user_agent;

        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (stripos($userAgent, 'Opera') !== false) {
            return 'Opera';
        } else {
            return 'Browser inconnu';
        }
    }

    /**
     * Get OS from user agent
     */
    public function getOsAttribute()
    {
        $userAgent = $this->user_agent;

        if (stripos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (stripos($userAgent, 'Mac') !== false) {
            return 'macOS';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (stripos($userAgent, 'iPhone') !== false) {
            return 'iOS';
        } elseif (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        } else {
            return 'Système inconnu';
        }
    }
}
