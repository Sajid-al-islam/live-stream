<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $livekitService;

    public function __construct(LiveKitService $livekitService)
    {
        $this->livekitService = $livekitService;
    }

    public function listRooms()
    {
        // Implement room listing logic
        // This would typically involve querying your database or LiveKit's room service
        return response()->json([
            'rooms' => [
                ['id' => 'room1', 'name' => 'Gaming Stream'],
                ['id' => 'room2', 'name' => 'Music Performance']
            ]
        ]);
    }
}
