@php
    $filePath = $record->file_path;
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $downloadUrl = route('document.download', ['document' => $record->id]);
    $previewUrl = $downloadUrl;
    $fileName = basename($filePath);
    
    $fileSize = '';
    try {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($filePath);
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $fileSize = $bytes . ' B';
            }
        }
    } catch (\Exception $e) {
        $fileSize = '';
    }
@endphp

<div class="space-y-4">
    <!-- Document Metadata Card -->
    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">
                {{ $record->title }}
            </h3>
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <span class="px-2.5 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full font-semibold">
                    {{ $record->category }}
                </span>
                <span>•</span>
                <span>Diarsipkan: {{ $record->upload_date ? $record->upload_date->format('d M Y') : '-' }}</span>
                @if($fileSize)
                    <span>•</span>
                    <span>Ukuran: {{ $fileSize }}</span>
                @endif
            </div>
        </div>
        
        <div>
            <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#005B2B] hover:bg-[#004D24] text-white rounded-lg shadow-sm text-xs font-semibold transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Unduh File
            </a>
        </div>
    </div>

    <!-- Preview Container -->
    <div class="w-full">
        @if ($extension === 'pdf')
            <!-- PDF.js Canvas-based Viewer (Bypasses IDM Interception) -->
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-150 dark:bg-gray-950">
                <div id="pdf-viewer-container" class="w-full h-[550px] overflow-y-auto p-4 flex flex-col items-center gap-4 scrollbar-thin">
                    <div id="pdf-loading" class="flex flex-col items-center justify-center py-20 gap-3">
                        <svg class="animate-spin h-8 w-8 text-[#005B2B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-gray-500 dark:text-gray-400 font-medium" id="pdf-loading-text">Memuat pratinjau dokumen...</span>
                    </div>
                </div>
            </div>

            <!-- PDF.js Stable CDN Integration -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
            <script>
                (function() {
                    const url = '{{ $previewUrl }}';
                    const container = document.getElementById('pdf-viewer-container');
                    const loadingEl = document.getElementById('pdf-loading');
                    const workerUrl = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

                    // Fallback timeout in case CDN fails or is blocked
                    const fallbackTimeout = setTimeout(function() {
                        if (loadingEl && !window.pdfjsLib) {
                            loadingEl.innerHTML = `
                                <div class="text-center p-6">
                                    <svg class="w-12 h-12 text-amber-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Gagal Memuat Engine Pratinjau</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Koneksi ke server CDN terhambat.</p>
                                    <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#005B2B] hover:bg-[#004D24] text-white rounded-lg shadow-sm text-xs font-semibold transition duration-200">
                                        Unduh Dokumen
                                    </a>
                                </div>
                            `;
                        }
                    }, 5000);

                    function initializePdf() {
                        pdfjsLib.getDocument(url).promise.then(function(pdf) {
                            clearTimeout(fallbackTimeout);
                            if (loadingEl) loadingEl.remove();

                            // 1. Create canvas elements synchronously in order first
                            const canvases = [];
                            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                                const wrapper = document.createElement('div');
                                wrapper.className = 'w-full flex justify-center mb-4';

                                const canvas = document.createElement('canvas');
                                canvas.className = 'shadow-md rounded-lg max-w-full bg-white border border-gray-200 dark:border-gray-800 hidden';
                                
                                wrapper.appendChild(canvas);
                                container.appendChild(wrapper);
                                canvases.push(canvas);
                            }

                            // 2. Render each page asynchronously on its pre-ordered canvas
                            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                                pdf.getPage(pageNum).then(function(page) {
                                    const scale = 1.3;
                                    const viewport = page.getViewport({ scale: scale });
                                    
                                    const canvas = canvases[pageNum - 1];
                                    const context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;
                                    canvas.classList.remove('hidden');

                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            }
                        }).catch(function(error) {
                            console.error('Error rendering PDF:', error);
                            clearTimeout(fallbackTimeout);
                            if (loadingEl) {
                                loadingEl.innerHTML = `
                                    <div class="text-center p-6">
                                        <svg class="w-12 h-12 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Gagal Memuat Dokumen</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Struktur dokumen rusak atau tidak dikenali.</p>
                                    </div>
                                `;
                            }
                        });
                    }

                    // Load worker as Blob to bypass browser cross-origin Web Worker blocks
                    if (window.pdfjsLib) {
                        fetch(workerUrl)
                            .then(function(response) {
                                if (!response.ok) throw new Error("Worker load failed");
                                return response.blob();
                            })
                            .then(function(blob) {
                                const blobUrl = URL.createObjectURL(blob);
                                pdfjsLib.GlobalWorkerOptions.workerSrc = blobUrl;
                                initializePdf();
                            })
                            .catch(function(error) {
                                console.warn("Failed to load PDF worker as blob, trying direct fallback", error);
                                pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;
                                initializePdf();
                            });
                    }
                })();
            </script>
        @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
            <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-750 max-h-[550px] overflow-hidden">
                <img src="{{ $previewUrl }}" alt="{{ $record->title }}" class="max-w-full max-h-[500px] object-contain rounded-lg shadow-sm" />
            </div>
        @else
            <div class="flex flex-col items-center justify-center p-8 bg-gray-50 dark:bg-gray-900 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-center">
                <!-- Word Document Icon -->
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">Pratinjau Tidak Tersedia</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6">
                    Dokumen <strong>.{{ $extension }}</strong> tidak dapat dipratinjau secara langsung di browser. Silakan unduh dokumen untuk membuka dan melihat isinya.
                </p>
                <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#005B2B] hover:bg-[#004D24] text-white rounded-lg shadow-md text-sm font-semibold transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Unduh Dokumen Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
