<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoapClient;

class NotifyViaSms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $phone;

    protected string $pattern;

    protected array $data;

    public function __construct(string $phone, string $pattern, array $data)
    {
        $this->phone = $phone;
        $this->pattern = $pattern;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \SoapFault
     */
    public function handle()
    {
        $url = 'http://ippanel.com/class/sms/wsdlservice/server.php?wsdl';
        $user = config('app.sms_uname');
        $pass = config('app.sms_pass');
        $from = '+983000505';
        $pattern_code = $this->pattern;
        $input_data = $this->data;

        $client = new SoapClient($url);

        $client->sendPatternSms(
            $from,
            $this->phone,
            $user,
            $pass,
            $pattern_code,
            $input_data
        );
    }
}
