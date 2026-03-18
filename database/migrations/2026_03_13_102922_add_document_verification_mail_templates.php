<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('mail_templates')->insert([
            [
                'mail_type' => 'document_verification_approved',
                'mail_subject' => 'Your Onboarding Documents have been Approved',
                'mail_body' => '<p>Hello {username},</p><p>Great news! Your onboarding documents have been approved by our team. You can now start managing your perahu and locations.</p><p>Best regards,<br>{website_title}</p>',
            ],
            [
                'mail_type' => 'document_verification_rejected',
                'mail_subject' => 'Action Required: Your Onboarding Documents were Rejected',
                'mail_body' => '<p>Hello {username},</p><p>We regret to inform you that your onboarding documents were rejected for the following reason:</p><p><strong>{rejection_reason}</strong></p><p>Please log in to your dashboard and re-upload the corrected documents.</p><p>Best regards,<br>{website_title}</p>',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('mail_templates')->whereIn('mail_type', ['document_verification_approved', 'document_verification_rejected'])->delete();
    }
};
