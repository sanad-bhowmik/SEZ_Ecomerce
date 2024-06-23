<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Models\SocialProvider;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\Notification;

class PushNotificationController extends Controller
{
    public function setFcmToken(Request $request)
    {
        try {
            DeviceToken::create(['user_id'=>$request->user('api')->id,'device_token'=>$request->device_token]);   
            $response = [
                'success' => true,
                'message' => 'Successfully set fcm token',
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }
}
