<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;

class RoomController extends Controller
{
    public function index()
    {
        // Ambil kamar aktif, urutkan harga
        $rooms = Room::with(['facilities' => function ($q) {
                $q->where('is_active', true);
            }])
            ->where('is_active', true)
            ->orderBy('monthly_price', 'asc')
            ->get();

        // Ambil semua fasilitas aktif, group nanti di view
        $facilities = Facility::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('user.rooms', compact('rooms', 'facilities'));
    }

    public function show($slug)
    {
        $room = Room::with('facilities')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('user.room-detail', compact('room'));
    }
}