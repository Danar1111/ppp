<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Office;
use App\Models\User as Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Show event attendance form for authenticated members.
     */
    public function showEventForm(Event $event)
    {
        $member = auth()->user();
        
        // Check if already checked in
        $exists = Attendance::where('member_id', $member->id)
            ->where('event_id', $event->id)
            ->exists();

        return view('attendance.event', compact('event', 'member', 'exists'));
    }

    /**
     * Submit event attendance for authenticated members.
     */
    public function submitEventAttendance(Request $request, Event $event)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $member = auth()->user();

        // Check if already scanned for this event
        $exists = Attendance::where('member_id', $member->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Anda sudah melakukan presensi untuk kegiatan ini.');
        }

        // Geofencing verification if coordinates are set
        if (!empty($event->latitude) && !empty($event->longitude)) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $event->latitude,
                $event->longitude
            );

            if ($distance > 50) {
                return back()->withErrors([
                    'location' => 'Anda berada terlalu jauh dari lokasi kegiatan (' . round($distance, 1) . ' meter). Batas maksimal adalah 50 meter.'
                ])->withInput();
            }
        }

        Attendance::create([
            'member_id' => $member->id,
            'event_id' => $event->id,
            'type' => 'Kegiatan',
            'scanned_at' => now(),
            'location_lat' => $request->latitude,
            'location_lng' => $request->longitude,
        ]);

        return back()->with('success', 'Presensi kegiatan "' . $event->name . '" berhasil direkam.');
    }

    /**
     * Show daily office check-in form.
     */
    public function showHarianForm(Office $office)
    {
        $member = auth()->user();

        // Check if already checked in today for this office
        $exists = Attendance::where('member_id', $member->id)
            ->where('type', 'Harian Kantor')
            ->where('office_id', $office->id)
            ->whereDate('scanned_at', now()->toDateString())
            ->exists();

        return view('attendance.harian', compact('office', 'member', 'exists'));
    }

    /**
     * Submit daily office check-in.
     */
    public function submitHarianAttendance(Request $request, Office $office)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $member = auth()->user();

        // Check if already checked in today for this office
        $exists = Attendance::where('member_id', $member->id)
            ->where('type', 'Harian Kantor')
            ->where('office_id', $office->id)
            ->whereDate('scanned_at', now()->toDateString())
            ->exists();

        if ($exists) {
            return back()->with('info', 'Anda sudah melakukan presensi harian kantor hari ini.');
        }

        // Geofencing verification
        if (!empty($office->latitude) && !empty($office->longitude)) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $office->latitude,
                $office->longitude
            );

            $radius = $office->radius_meters ?: 50;

            if ($distance > $radius) {
                return back()->withErrors([
                    'location' => 'Anda berada terlalu jauh dari lokasi kantor (' . round($distance, 1) . ' meter). Batas maksimal adalah ' . $radius . ' meter.'
                ])->withInput();
            }
        }

        Attendance::create([
            'member_id' => $member->id,
            'office_id' => $office->id,
            'event_id' => null,
            'type' => 'Harian Kantor',
            'scanned_at' => now(),
            'location_lat' => $request->latitude,
            'location_lng' => $request->longitude,
        ]);

        return back()->with('success', 'Presensi Harian Kantor (' . $office->name . ') berhasil direkam.');
    }

    /**
     * Step 1: Scan QR (NIK) and fetch member details via AJAX.
     */
    public function scanDetails(Request $request, Event $event)
    {
        $request->validate([
            'nik' => 'required|string',
        ]);

        $member = Member::with('village')->where('nik', $request->nik)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak ditemukan dalam database anggota.',
            ], 404);
        }

        // Check if already scanned
        $alreadyScanned = Attendance::where('member_id', $member->id)
            ->where('event_id', $event->id)
            ->exists();

        // Get photo URL
        $photoUrl = null;
        if ($member->photo) {
            $photoUrl = asset('storage/' . $member->photo);
        }

        return response()->json([
            'success' => true,
            'already_scanned' => $alreadyScanned,
            'member' => [
                'id' => $member->id,
                'nik' => $member->nik,
                'name' => $member->name,
                'status' => $member->status,
                'village_name' => $member->village ? $member->village->name : '-',
                'photo_url' => $photoUrl,
            ],
            'message' => $alreadyScanned ? 'Anggota ini sudah melakukan presensi sebelumnya.' : 'Anggota siap dikonfirmasi.',
        ]);
    }

    /**
     * Step 2: Confirm member attendance via AJAX.
     */
    public function scanConfirm(Request $request, Event $event)
    {
        $request->validate([
            'nik' => 'required|string',
        ]);

        $member = Member::where('nik', $request->nik)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak ditemukan dalam database.',
            ], 404);
        }

        // Check if already scanned
        $exists = Attendance::where('member_id', $member->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Anggota ' . $member->name . ' sudah presensi sebelumnya.',
                'already_scanned' => true,
            ]);
        }

        Attendance::create([
            'member_id' => $member->id,
            'event_id' => $event->id,
            'type' => 'Kegiatan',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat untuk: ' . $member->name,
            'member_name' => $member->name,
        ]);
    }

    // (getOfficeDetails was refactored and removed in favor of dynamic Office models)

    /**
     * Compute Haversine distance in meters between two GPS coordinates.
     */
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
