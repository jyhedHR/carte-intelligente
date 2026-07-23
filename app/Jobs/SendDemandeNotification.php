<?php

namespace App\Jobs;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDemandeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $demande;
    protected $status;

    public function __construct(Demande $demande, string $status)
    {
        $this->demande = $demande;
        $this->status = $status;
    }

    public function handle()
    {
        // Example: Send email
        Mail::raw("Your demande {$this->demande->reference} is now {$this->status}.", function ($message) {
            $message->to($this->demande->user->email)
                    ->subject('Demande Status Update');
        });

        // You can also log or store in the database
        Log::info("Notification sent for demande {$this->demande->id}: status = {$this->status}");
    }
}
