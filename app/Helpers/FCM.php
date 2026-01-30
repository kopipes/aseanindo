<?php
namespace App\Helpers;


use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

class FCM
{
    protected static $firebaseUrl = "https://fcm.googleapis.com/fcm/send";
    protected static $firebaseKey;
    protected static $regIds = [];
    protected static $platform;

    public function __construct()
    {
        self::$firebaseKey  =  config('services.firebase_key');
    }

    public static function post($regId,$platform = 'android')
    {
        self::$platform = $platform;
        self::$regIds = [$regId];
        return new static();
    }
    public static function ios($regIds = [])
    {
        self::$platform = "ios";
        self::$regIds = $regIds;
        return new static();
    }

    public static function android($regIds = [])
    {
        self::$platform = "android";
        self::$regIds = $regIds;
        return new static();
    }


    /**
     * dispatch function to send fcm notification as queue
     */
    public static function dispatch($data)
    {
        $regIds = self::$regIds;
        $platform = self::$platform;
        $firebaseUrl = self::$firebaseUrl;
        Bus::chain([
            function () use ($data, $regIds, $platform, $firebaseUrl) {
                self::sendFirebaseNotification(
                    $data,
                    $regIds,
                    $platform,
                    $firebaseUrl,
                );
            },
        ])->dispatch();
    }
    
    public static function send($data)
    {
        self::sendFirebaseNotification(
            data: $data,
            regIds: self::$regIds,
            platform: self::$platform,
            firebaseUrl: self::$firebaseUrl
        );
    }

    public static function sendFirebaseNotification($data, $regIds, $platform, $firebaseUrl)
    {
        $firebaseKey  =  config('services.firebase_key');
        if (count($regIds)) {
            $regIds = array_values(array_unique($regIds));

            $postData = [
                'registration_ids' => $regIds,
                'data' => $data,
                'content_available' => true,
                'priority' => 'high',
            ];

            if ($platform == "ios") {
                $postData['notification'] = [
                    'sound' => 'default',
                    'title' => trim(strip_tags(@$data['title'])),
                    'body' => trim(strip_tags(@$data['message'])),
                ];
            }
            $response = Http::withHeaders([
                'Authorization' => "key=" . $firebaseKey
            ])->post($firebaseUrl, $postData);
            logger($response->body());
            return  $response->json();
        }
    }
}
