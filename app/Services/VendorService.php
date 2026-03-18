<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\Language;
use App\Models\BasicSettings\MailTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Mail\Message;

class VendorService
{
    /**
     * Handle vendor registration logic.
     */
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['dob'] = $data['dob'];
        $data['phone'] = $data['phone'];
        $data['terms_and_conditions'] = (isset($data['terms_and_conditions']) && $data['terms_and_conditions'] == 'on') ? 1 : 0;

        // Handle Documents
        $data = $this->uploadDocuments($data);

        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

        // Default status based on approval setting
        $data['status'] = ($setting->vendor_admin_approval == 1) ? Vendor::STATUS_DEACTIVE : Vendor::STATUS_ACTIVE;

        if ($setting->vendor_email_verification == 1) {
            $this->sendVerificationMail($data);
            $data['status'] = Vendor::STATUS_DEACTIVE;
        } else {
            $data['email_verified_at'] = now();
            Session::flash('success', __('Sign up successfully completed. Please Login Now') . '!');
        }

        $language = Language::where('is_default', 1)->first();
        $data['lang_code'] = 'admin_' . $language->code;
        $data['code'] = $language->code;
        $data['to_mail'] = $data['email'];

        $vendor = Vendor::create($data);

        // Create Vendor Info
        VendorInfo::create([
            'vendor_id' => $vendor->id,
            'language_id' => $language->id,
            'email' => $data['email'],
            'username' => $data['username']
        ]);

        return $vendor;
    }

    /**
     * Upload registration documents to private storage.
     */
    protected function uploadDocuments(array $data)
    {
        $documentFields = ['ktp_file', 'boat_ownership_file', 'driving_license_file', 'self_photo_file'];
        foreach ($documentFields as $field) {
            if (request()->hasFile($field)) {
                $file = request()->file($field);
                $fileName = uniqid() . '-' . $field . '.' . $file->getClientOriginalExtension();
                // Store in storage/app/private/vendor-documents/
                $path = $file->storeAs('private/vendor-documents', $fileName);
                $data[$field] = $fileName;
            }
        }
        return $data;
    }

    /**
     * Send email verification.
     */
    protected function sendVerificationMail(array $data)
    {
        $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();
        if (!$mailTemplate) return;

        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $link = '<a href=' . url("vendor/email/verify?token=" . $data['email']) . '>Click Here</a>';
        $body = str_replace(
            ['{username}', '{verification_link}', '{website_title}'],
            [$data['username'], $link, $info->website_title],
            $mailTemplate->mail_body
        );

        if ($info->smtp_status == 1) {
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $info->smtp_host,
                'port' => $info->smtp_port,
                'encryption' => $info->encryption,
                'username' => $info->smtp_username,
                'password' => $info->smtp_password,
            ]);

            Mail::send([], [], function (Message $message) use ($data, $info, $mailTemplate, $body) {
                $message->to($data['email'])
                    ->subject($mailTemplate->mail_subject)
                    ->from($info->from_mail, $info->from_name)
                    ->html($body, 'text/html');
            });
            Session::flash('success', __('A verification mail has been sent to your email address') . '!');
        }
    }
}
