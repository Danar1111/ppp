@extends('public.layouts.app')

@section('title', 'Kabar & Publikasi | Partai Persatuan Pembangunan')

@section('content')
    <!-- Header Section -->
    <section class="bg-slate-900 text-white py-16 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Kabar & Publikasi PPP</h1>
            <p class="text-slate-400 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                Temukan informasi terbaru, pengumuman resmi, agenda kerja, dan berita terkini mengenai perjuangan Partai Persatuan Pembangunan.
            </p>
        </div>
    </section>

    <!-- News Grid Section -->
    <section class="py-16 bg-slate-50 min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($articles as $article)
                    <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full">
                        <div class="relative h-48 bg-slate-200 overflow-hidden">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#005B2B]/30 to-[#005B2B]/10 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-[#005B2B]/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <time class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                                    {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                                </time>
                                <h2 class="font-bold text-slate-800 text-lg hover:text-[#005B2B] transition line-clamp-2">
                                    <a href="{{ route('public.berita.show', $article->slug) }}">{{ $article->title }}</a>
                                </h2>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                            </div>
                            <a href="{{ route('public.berita.show', $article->slug) }}" class="inline-flex items-center text-xs font-extrabold text-[#005B2B] hover:text-[#D97706] gap-1 group">
                                Baca Selengkapnya
                                <span class="group-hover:translate-x-0.5 transition duration-200">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 py-16 text-center text-slate-400 text-sm bg-white rounded-3xl border border-slate-200">
                        Belum ada berita yang dipublikasikan saat ini.
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        </div>
    </section>
@endsection
