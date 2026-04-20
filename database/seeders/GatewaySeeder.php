<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway\OnlineGateway;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            'paypal', 'razorpay', 'instamojo', 'paystack', 'flutterwave', 
            'mollie', 'mercadopago', 'paytm', 'midtrans', 'stripe', 'iyzico', 'authorize.net'
        ];

        $allKeys = [
            'client_id', 'client_secret', 'sandbox_status', 'key', 'secret', 
            'token', 'public_key', 'secret_key', 'api_key', 'access_token', 
            'merchant_id', 'merchant_key', 'website', 'industry_type', 
            'channel', 'server_key', 'client_key', 'is_production',
            'login_id', 'transaction_key', 'sandbox_check'
        ];

        $dummyInfo = [];
        foreach ($allKeys as $k) {
            $dummyInfo[$k] = 'dummy';
        }
        $dummyInfo['sandbox_status'] = 1;
        $dummyInfo['is_production'] = 0;
        $dummyInfo['sandbox_check'] = 1;

        foreach ($gateways as $keyword) {
            OnlineGateway::updateOrCreate(
                ['keyword' => $keyword],
                [
                    'name' => ucfirst(str_replace('.', ' ', $keyword)),
                    'status' => 1,
                    'information' => json_encode($dummyInfo)
                ]
            );
        }
    }
}
