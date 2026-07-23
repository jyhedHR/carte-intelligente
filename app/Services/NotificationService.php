<?php
// app/Services/NotificationService.php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function createNotification(
        int $userId,
        string $message,
        string $type = 'INFO',
        string $canal = 'WEB',
        ?string $processInstanceId = null,
        ?string $taskId = null,
        ?string $adminComment = null,
        ?string $reference = null
    ): Notification {
        return Notification::create([
            'id_user' => $userId,
            'heure' => Carbon::now(),
            'message' => $message,
            'canal' => $canal,
            'type' => $type,
            'lu' => false,
            'envoye_a' => null,
            'expires_at' => Carbon::now()->addDays(30),
            'process_instance_id' => $processInstanceId,
            'task_id' => $taskId,
            'admin_comment' => $adminComment,
            'reference' => $reference
        ]);
    }

    /**
     * Send approval notification
     */
    public function sendApprovalNotification(
        int $userId,
        string $processName,
        string $adminComment,
        ?string $processInstanceId = null,
        ?string $taskId = null,
        ?string $reference = null
    ): Notification {
        $message = "✅ Votre demande '{$processName}' a été approuvée.";

        if ($adminComment) {
            $message .= " Commentaire: {$adminComment}";
        }

        return $this->createNotification(
            $userId,
            $message,
            'APPROVE',
            'WEB',
            $processInstanceId,
            $taskId,
            $adminComment,
            $reference
        );
    }

    /**
     * Send rejection notification
     */
    public function sendRejectionNotification(
        int $userId,
        string $processName,
        string $reason,
        string $adminComment,
        ?string $processInstanceId = null,
        ?string $taskId = null,
        ?string $reference = null
    ): Notification {
        $message = "❌ Votre demande '{$processName}' a été rejetée.";
        $message .= " Motif: {$reason}.";

        if ($adminComment) {
            $message .= " Commentaire: {$adminComment}";
        }

        return $this->createNotification(
            $userId,
            $message,
            'REJECT',
            'WEB',
            $processInstanceId,
            $taskId,
            $adminComment,
            $reference
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('id_user', $userId)
            ->first();

        if ($notification && !$notification->lu) {
            $notification->lu = true;
            $notification->save();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('id_user', $userId)
            ->where('lu', false)
            ->update(['lu' => true]);
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('id_user', $userId)
            ->where('lu', false)
            ->count();
    }

    /**
     * Get user notifications with pagination
     */
    public function getUserNotifications(int $userId, int $perPage = 20)
    {
        return Notification::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Delete old read notifications manually (if needed)
     */
    public function cleanupOldNotifications(): int
    {
        return Notification::where('lu', true)
            ->where('updated_at', '<=', now()->subDays(30))
            ->delete();
    }
}
