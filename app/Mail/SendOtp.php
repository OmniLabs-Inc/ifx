<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DefaultTemplate;
use App\Models\Template;
use Illuminate\Support\Str;


class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp;
    protected $otp_type;
    protected $otp_temp_type;
    protected $link;

    public function __construct($otp_type, $otp, $otp_temp_type, $link)
    {
        $this->otp = $otp;
        $this->otp_type = $otp_type;
        $this->otp_temp_type = $otp_temp_type;
        $this->link = $link;
    }


    public function getTemplateHtml($otp, $type, $temp_type, $link)
    {

        # Firstly getting html from admin selected templates
        // $html = Template::where(['type' => $type, 'temp_type' => $temp_type, 'status' => '1'])->value('content');

        // # If admin is not added template so we are getting default Template
        // if ($html == '') {
        //     $html = DefaultTemplate::where(['type' => $type, 'temp_type' => $temp_type])->value('content');
        // }

        $html = '';

        # If we don't have any Template
        if ($html == '') {

            if ($temp_type != 'register') {
                $html = 'This OTP {otp} For {otp-type} From SIP-FX. Here is link to verify :- <a href="{link}"> Click Here  </a>';
            }

          	if ($temp_type == 'send_unique_id') {
                $html = 'This is Unique id From SIP-FX :- {link} . Now you can login account with this unique id.';
            }
        }

        $Str1 = Str::replace('{otp}', $otp, $html);

        $Str2 = Str::replace('{otp-type}', $type, $Str1);

        $Str3 = Str::replace('{link}', $link, $Str2);

        if (str_contains($otp, 'token=')) {
            $token = explode("token=", $otp);
            $Str3 = Str::replace('{link-token}', $token[1], $Str3);
        }

        return $Str3;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = $this->getTemplateHtml($this->otp, $this->otp_type, $this->otp_temp_type, $this->link);

        return $this->subject(ucfirst($this->otp_type))->html($html);
    }
}
