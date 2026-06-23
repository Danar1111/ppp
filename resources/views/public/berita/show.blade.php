@extends('public.layouts.app')

@section('title', $article->title . ' | Partai Persatuan Pembangunan')

@section('content')
    <article class="py-16 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Breadcrumbs / Back button -->
            <div>
                <a href="{{ route('public.berita.index') }}" class="inline-flex items-center text-xs font-bold text-[#005B2B] hover:text-[#D97706] gap-1 group">
                    <span>&larr;</span> Kembali ke Berita
                </a>
            </div>

            <!-- Card Wrap -->
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <!-- Cover Image -->
                <div class="relative h-[300px] sm:h-[400px] w-full bg-slate-900 overflow-hidden">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#005B2B] to-slate-950 flex items-center justify-center">
                            <span class="text-white/20 font-black text-6xl uppercase select-none">PPP</span>
                        </div>
                    @endif
                </div>

                <!-- Main Content Body -->
                <div class="p-8 sm:p-12 space-y-6">
                    <div class="space-y-4 border-b border-slate-100 pb-6">
                        <time class="text-xs font-bold text-[#D97706] uppercase tracking-wider">
                            Diterbitkan: {{ $article->published_at ? $article->published_at->format('d F Y H:i') : $article->created_at->format('d F Y H:i') }} WIB
                        </time>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            {{ $article->title }}
                        </h1>
                    </div>

                    <!-- Rendered Rich Editor Content -->
                    <div class="article-content text-slate-700 text-sm sm:text-base leading-relaxed select-text">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>
        </div>
    </article>

    <style>
        .article-content p {
            margin-bottom: 1.25rem;
            line-height: 1.8;
        }
        .article-content h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        .article-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        .article-content ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
            space-y: 0.5rem;
        }
        .article-content ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
            space-y: 0.5rem;
        }
        .article-content li {
            margin-bottom: 0.5rem;
        }
        .article-content blockquote {
            border-left: 4px solid #D97706;
            padding-left: 1rem;
            font-style: italic;
            color: #475569;
            margin: 1.5rem 0;
        }
        .article-content a {
            color: #005B2B;
            text-decoration: underline;
            font-weight: 600;
        }
        .article-content a:hover {
            color: #D97706;
        }
    </style>
@endsection
