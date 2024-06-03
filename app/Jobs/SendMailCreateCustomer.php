<?php

namespace App\Jobs;

use App\Customer;
use App\Mail\VerifyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailCreateCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email , Customer $customer)
    {
        $this->email = $email;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new VerifyMail($this->customer));
    }
}
