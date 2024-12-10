<?php

namespace App\Http\Controllers;

use App\Services\LiveKitService;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    protected $livekitService;

    public function __construct(LiveKitService $livekitService)
    {
        $this->livekitService = $livekitService;
    }

    public function startStream(Request $request)
    {
        $roomName = $request->input('room_name');
        $identity = $request->input('identity');

        // Create room
        $this->livekitService->createRoom($roomName);

        // Generate access token
        $token = $this->livekitService->createAccessToken($roomName, $identity);

        return response()->json([
            'token' => $token,
            'room' => $roomName
        ]);
    }

    public function joinStream(Request $request)
    {
        $roomName = $request->input('room_name');
        $identity = $request->input('identity');

        // Generate access token for joining
        $token = $this->livekitService->createAccessToken($roomName, $identity);

        return response()->json([
            'token' => $token,
            'room' => $roomName
        ]);
    }
}
