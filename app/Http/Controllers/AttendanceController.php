<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
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
    public function showHarianForm(string $level, string $code)
    {
        $member = auth()->user();
        $office = $this->getOfficeDetails($level, $code);

        // Check if already checked in today
        $exists = Attendance::where('member_id', $member->id)
            ->where('type', 'Harian Kantor')
            ->whereDate('scanned_at', now()->toDateString())
            ->exists();

        return view('attendance.harian', compact('office', 'level', 'code', 'member', 'exists'));
    }

    /**
     * Submit daily office check-in.
     */
    public function submitHarianAttendance(Request $request, string $level, string $code)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $member = auth()->user();
        $office = $this->getOfficeDetails($level, $code);

        // Check if already checked in today
        $exists = Attendance::where('member_id', $member->id)
            ->where('type', 'Harian Kantor')
            ->whereDate('scanned_at', now()->toDateString())
            ->exists();

        if ($exists) {
            return back()->with('info', 'Anda sudah melakukan presensi harian kantor hari ini.');
        }

        // Geofencing verification
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $office['latitude'],
            $office['longitude']
        );

        if ($distance > 50) {
            return back()->withErrors([
                'location' => 'Anda berada terlalu jauh dari lokasi kantor (' . round($distance, 1) . ' meter). Batas maksimal adalah 50 meter.'
            ])->withInput();
        }

        Attendance::create([
            'member_id' => $member->id,
            'event_id' => null,
            'type' => 'Harian Kantor',
            'scanned_at' => now(),
            'location_lat' => $request->latitude,
            'location_lng' => $request->longitude,
        ]);

        return back()->with('success', 'Presensi Harian Kantor (' . $office['name'] . ') berhasil direkam.');
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

    /**
     * Retrieve office details based on level and region code.
     */
    protected function getOfficeDetails(string $level, string $code): array
    {
        $level = strtoupper($level);
        $name = "Kantor Sekretariat PPP";
        
        // Base mock coordinate (used as default / fallback, e.g. Central Jakarta Office)
        $latitude = -6.2088;
        $longitude = 106.8456;

        if ($level === 'DPP') {
            $name = "Kantor Pusat DPP PPP";
            $latitude = -6.2088; // Jakarta
            $longitude = 106.8456;
        } elseif ($level === 'DPW') {
            $province = \App\Models\Province::where('code', $code)->first();
            $provName = $province ? $province->name : 'Wilayah';
            $name = "Kantor DPW PPP Provinsi " . $provName;
            // Mock coordinate for DPW (Bandung)
            $latitude = -6.9175;
            $longitude = 107.6191;
        } elseif ($level === 'DPC') {
            $regency = \App\Models\Regency::where('code', $code)->first();
            $regName = $regency ? $regency->name : 'Cabang';
            $name = "Kantor DPC PPP " . $regName;
            // Mock coordinate for DPC (Surabaya)
            $latitude = -7.2575;
            $longitude = 112.7521;
        } elseif ($level === 'PAC') {
            $district = \App\Models\District::where('code', $code)->first();
            $distName = $district ? $district->name : 'Kecamatan';
            $name = "Kantor PAC PPP Kecamatan " . $distName;
            // Mock coordinate for PAC (Yogyakarta)
            $latitude = -7.7956;
            $longitude = 110.3695;
        }

        // To make testing extremely easy and avoid hard blocks on local,
        // if code is or contains 'test', make it inherit coordinates to avoid failures.
        if (str_contains(strtolower($code), 'test')) {
            $name .= " (Mode Uji Coba)";
        }

        return [
            'name' => $name,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

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
