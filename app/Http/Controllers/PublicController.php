<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use App\Models\User as Member;
use App\Models\Committee;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // 3 latest Published articles
        $articles = Article::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        // Upcoming and ongoing events
        $events = Event::where('start_datetime', '>=', now()->subDay())
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get();

        return view('public.home', compact('articles', 'events'));
    }

    public function beritaIndex()
    {
        // Published articles, paginated (e.g., 6 per page)
        $articles = Article::published()
            ->latest('published_at')
            ->paginate(6);

        return view('public.berita.index', compact('articles'));
    }

    public function beritaShow($slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.berita.show', compact('article'));
    }

    public function struktur()
    {
        // Query committees with active members grouped by position level
        $committees = Committee::with(['member.village', 'position', 'province', 'regency', 'district', 'village'])
            ->whereHas('member', function ($q) {
                $q->where('status', 'Aktif');
            })
            ->get()
            ->groupBy(function ($c) {
                return $c->position->level; // DPP, DPW, DPC, PAC, Ranting
            });

        return view('public.struktur', compact('committees'));
    }

    public function cekNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        $nik = $request->input('nik');
        
        $member = Member::with('village')->where('nik', $nik)->first();

        if ($member) {
            if ($member->status === 'Aktif') {
                $villageName = $member->village ? $member->village->name : '-';
                return response()->json([
                    'success' => true,
                    'message' => "Valid: {$member->name} terdaftar sebagai Anggota di Kelurahan/Desa {$villageName}."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Status keanggotaan NIK {$nik} saat ini adalah '{$member->status}'. Silakan hubungi admin."
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan. Silakan hubungi kantor cabang terdekat.'
        ]);
    }
}
