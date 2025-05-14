<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;


class Notifications extends Component
{  public $notificationCount = 0;
    public $notifications = [];

    public function mount()
    {
        if (Auth::check() && Auth::user()->pharmacies) {
            $pharmacyUserId = Auth::user()->pharmacies->user_id;

            // Count unread notifications
            $this->notificationCount = DatabaseNotification::where('notifiable_id', $pharmacyUserId)
                ->where('notifiable_type', 'App\\Models\\User')
                ->whereNull('read_at')
                ->count();

            // Get the latest notifications
            $this->notifications = DatabaseNotification::where('notifiable_id', $pharmacyUserId)
                ->where('notifiable_type', 'App\\Models\\User')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }
    }
//     public function markAsRead($notificationId)
// {
//     $notification = Notification::find($notificationId);
//     if ($notification) {
//         $notification->update(['read_at' => now()]);
//         $this->notificationCount--; // Decrease the unread count
//         $this->notifications = $this->notifications->reject(function ($notif) use ($notificationId) {
//             return $notif->id == $notificationId;
//         }); // Remove the notification from the list
//     }
// }

    public function render()
    {
        return view('livewire.notifications');
    }
}
