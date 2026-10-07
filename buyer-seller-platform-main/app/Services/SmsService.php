<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendOtp($phone, $otp)
    {
        $userId = config('services.nimbus_sms.user_id');
        $password = config('services.nimbus_sms.password');
        $senderId = config('services.nimbus_sms.sender_id');
        $templateId = config('services.nimbus_sms.template_id');
        $entityId = config('services.nimbus_sms.pe_id');
        $messageTemplate = config('services.nimbus_sms.message_template');
        $apiUrl = config('services.nimbus_sms.api_url', 'http://nimbusit.biz/api/SmsApi/SendSingleApi');

        if (!$userId || !$password || !$senderId || !$templateId || !$entityId || !$messageTemplate) {
            Log::warning('SmsService: Nimbus SMS is not fully configured. OTP SMS was skipped.', [
                'phone' => $phone,
            ]);
            return false;
        }

        $message = str_replace(':otp', $otp, $messageTemplate);

        try {
            $response = Http::get($apiUrl, [
                'UserID' => $userId,
                'Password' => $password,
                'SenderID' => $senderId,
                'Phno' => $phone,
                'Msg' => $message,
                'EntityID' => $entityId,
                'TemplateID' => $templateId
            ]);

            $responseData = $response->json();

            if (isset($responseData['Status']) && $responseData['Status'] !== 'OK') {
                dd([
                    'Nimbus_API_Error' => $responseData['Response']['Message'] ?? 'Unknown Error',
                    'Hint' => 'Check if SenderID, TemplateID, or EntityID is correct. Also ensure the message matches the DLT template.',
                    'Sent_Parameters' => [
                        'UserID' => $userId,
                        'SenderID' => $senderId,
                        'Phno' => $phone,
                        'EntityID' => $entityId,
                        'TemplateID' => $templateId,
                        'Message' => $message
                    ]
                ]);
            }

            if ($response->successful()) {
                Log::info("SmsService: OTP sent successfully to $phone. Response: " . $response->body());
                return true;
            }

            Log::error("SmsService: Failed to send OTP to $phone. Status: " . $response->status() . " Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("SmsService: Exception while sending OTP to $phone. Error: " . $e->getMessage());
            return false;
        }
    }
}
