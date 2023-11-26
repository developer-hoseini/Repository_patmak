<?php

namespace App\Jobs;

use App\Facades\SmsFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiver;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiver, $message)
    {
        $this->receiver = $receiver;

        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SmsFacade $sms)
    {
        $sms::sendText($this->receiver, $this->message);
    }
}
