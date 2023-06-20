<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    public function sendWhatsAppMessage(Request $request)
    {
        $accountSid = config('app.twilio_sid');
        $authToken = config('app.twilio_auth_token');
        $twilioNumber = config('app.twilio_whatsapp_number');
        
        $client = new Client($accountSid, $authToken);
        
        $message = $client->messages->create(
            $request->input('to'),
            [
                'from' => $twilioNumber,
                'body' => $request->input('message')
            ]
        );
        
        return response()->json(['message' => 'WhatsApp message sent successfully']);
    }
}