<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class LoanStatusNotification extends Notification {
 use Queueable;
 public function __construct(private string $title, private string $message, private string $url='/dashboard') {}
 public function via(object $notifiable): array { return ['database']; }
 public function toArray(object $notifiable): array { return ['title'=>$this->title,'message'=>$this->message,'url'=>$this->url]; }
}