<div id="command-center-canvas" 
     x-data="commandCenterMap()"
     x-init="initMap()"
     class="relative w-full h-[calc(100vh-4rem)] overflow-hidden">

    <!-- Leaflet Assets via CDN (CSS only; JS is provided globally by dotswan package) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Custom Scoped CSS to Make Map Full Width and Height -->
    <style>
        #command-center-canvas {
            z-index: 1;
        }
        /* Scoped to CommandCenter page via :has selector */
        :has(#command-center-canvas) .fi-header {
            display: none !important;
        }
        :has(#command-center-canvas) .fi-main-ctn {
            padding: 0 !important;
        }
        :has(#command-center-canvas) .fi-main {
            padding: 0 !important;
            max-width: 100% !important;
        }
        :has(#command-center-canvas) .fi-page {
            padding: 0 !important;
        }
        /* Custom map popup styles */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            padding: 0 !important;
            overflow: hidden !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1) !important;
        }
        .leaflet-popup-content {
            margin: 0 !important;
            width: 240px !important;
        }
        .leaflet-popup-tip {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
        /* Custom map markers */
        .custom-div-icon-tps,
        .custom-div-icon-event,
        .custom-div-icon-office {
            background: none !important;
            border: none !important;
            pointer-events: auto !important;
        }
        .custom-div-icon-tps div,
        .custom-div-icon-event div,
        .custom-div-icon-office div {
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        }
        .custom-div-icon-tps:hover div,
        .custom-div-icon-event:hover div,
        .custom-div-icon-office:hover div {
            transform: scale(1.2) !important;
        }
        @keyframes pulse-gold {
            0% {
                box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7), 0 3px 6px rgba(0,0,0,0.3);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(217, 119, 6, 0), 0 3px 6px rgba(0,0,0,0.3);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(217, 119, 6, 0), 0 3px 6px rgba(0,0,0,0.3);
            }
        }
        /* Stacking context overrides to force popup on top of all map layers */
        .leaflet-map-pane {
            z-index: 100 !important;
        }
        .leaflet-marker-pane {
            z-index: 600 !important;
        }
        .leaflet-popup-pane {
            z-index: 99999 !important;
        }
        .leaflet-popup {
            z-index: 99999 !important;
        }
    </style>

    <!-- Leaflet Map Container -->
    <div id="map" wire:ignore class="w-full h-full"></div>

    <!-- Livewire-to-Alpine Reactive Data Bridge -->
    <div wire:key="map-updater-{{ md5(json_encode($this->getTpsData()) . json_encode($this->getEventsData()) . json_encode($this->getOfficesData())) }}"
         x-init="
            $nextTick(() => {
                updateTps({{ json_encode($this->getTpsData()) }});
                updateEvents({{ json_encode($this->getEventsData()) }});
                updateOffices({{ json_encode($this->getOfficesData()) }});
            });
         "
         style="display: none;">
    </div>

    <!-- Floating Control Panel -->
    <div class="absolute top-4 right-4 z-[1000] w-72 backdrop-blur-md bg-white/95 border border-slate-200/60 shadow-2xl rounded-2xl p-5 select-none transition-all duration-300">
        <!-- Panel Header -->
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-[#005B2B]/10 flex items-center justify-center text-[#005B2B]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.446 1.202-.832a2.207 2.207 0 0 0 .522-3.11l-1.202-.832a2.207 2.207 0 0 0-3.11-.522l-1.202.832a2.207 2.207 0 0 0-.522 3.11l1.202.832a2.207 2.207 0 0 0 3.11.522Zm-6-7.5H4.25A2.25 2.25 0 0 0 2 11.25v7.5A2.25 2.25 0 0 0 4.25 21h4.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25Zm0 0V3.75A2.25 2.25 0 0 1 11.25 1.5h7.5A2.25 2.25 0 0 1 21 3.75v3M9 15H4.25A2.25 2.25 0 0 0 2 17.25v1.5A2.25 2.25 0 0 0 4.25 21h4.5a2.25 2.25 0 0 0 2.25-2.25v-1.5A2.25 2.25 0 0 0 9 15Z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm tracking-tight">Peta Pemantauan</h3>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Pemenangan Pemilu</p>
            </div>
        </div>

        <!-- Filter Checkboxes -->
        <div class="space-y-4">
            <!-- TPS Layer Toggle -->
            <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 hover:border-[#005B2B]/20 hover:bg-[#005B2B]/5 transition-all duration-200 cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-500/10"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan TPS</span>
                        <span class="text-[10px] text-slate-400">Tempat Pemungutan Suara</span>
                    </div>
                </div>
                <input type="checkbox" 
                       wire:model.live="showTps" 
                       class="w-4.5 h-4.5 rounded border-slate-300 text-[#005B2B] focus:ring-[#005B2B] focus:ring-offset-0 transition-all duration-150 cursor-pointer">
            </label>

            <!-- Events Layer Toggle -->
            <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 hover:border-blue-500/20 hover:bg-blue-50/50 transition-all duration-200 cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-blue-500/10"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan Agenda</span>
                        <span class="text-[10px] text-slate-400">Kegiatan & Acara Partai</span>
                    </div>
                </div>
                <input type="checkbox" 
                       wire:model.live="showEvents" 
                       class="w-4.5 h-4.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 transition-all duration-150 cursor-pointer">
            </label>

            <!-- Offices Layer Toggle -->
            <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 hover:border-amber-500/20 hover:bg-amber-50/50 transition-all duration-200 cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-amber-500/10"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan Markas / Posko</span>
                        <span class="text-[10px] text-slate-400">Kantor Sekretariat Utama & Cabang</span>
                    </div>
                </div>
                <input type="checkbox" 
                       wire:model.live="showOffices" 
                       class="w-4.5 h-4.5 rounded border-slate-300 text-amber-600 focus:ring-amber-500 focus:ring-offset-0 transition-all duration-150 cursor-pointer">
            </label>
        </div>

        <!-- Legend and Summary Stats -->
        <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
            <div class="flex items-center justify-between text-[10px] font-medium text-slate-500">
                <span>Total Pin Aktif:</span>
                <span class="font-bold text-slate-700">{{ count($this->getTpsData()) + count($this->getEventsData()) + count($this->getOfficesData()) }}</span>
            </div>
            <div class="flex gap-2 flex-wrap text-[9px] font-medium text-slate-400">
                <div class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                    <span>TPS Selesai</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                    <span>TPS Pending</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                    <span>Agenda</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                    <span>Markas/Posko</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('commandCenterMap', () => ({
                map: null,
                tpsMarkers: [],
                eventMarkers: [],
                officeMarkers: [],

                initMap() {
                    // Coordinates centered around Sumedang, West Java (Default location)
                    this.map = L.map('map', {
                        zoomControl: false // Hide default zoom buttons to place custom or keep clean
                    }).setView([-6.8388, 107.9253], 12);

                    // Add clean zoom control on the bottom-right corner
                    L.control.zoom({
                        position: 'bottomright'
                    }).addTo(this.map);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.map);
                },

                updateTps(data) {
                    // Remove existing markers
                    this.tpsMarkers.forEach(m => this.map.removeLayer(m));
                    this.tpsMarkers = [];

                    // Add new markers
                    data.forEach(item => {
                        if (!item.latitude || !item.longitude) return;

                        // Color: Red for Pending, Green for Selesai
                        const color = item.status === 'Selesai' ? '#10B981' : '#EF4444';
                        
                        const markerIcon = L.divIcon({
                            className: 'custom-div-icon-tps',
                            html: `<div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer;"></div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });

                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);
                        const latStr = isNaN(lat) ? item.latitude : lat.toFixed(5);
                        const lngStr = isNaN(lng) ? item.longitude : lng.toFixed(5);

                        const marker = L.marker([item.latitude, item.longitude], { 
                            icon: markerIcon,
                            interactive: true
                        }).addTo(this.map);

                        marker.bindPopup(`
                            <div class="font-sans">
                                <div class="px-3 py-2 text-white flex items-center justify-between text-[10px] font-bold tracking-wide" style="background-color: ${color};">
                                    <span>TPS STATUS</span>
                                    <span class="uppercase">${item.status}</span>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-slate-800 text-sm">${item.name}</h4>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        ${item.village_name}
                                    </p>
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400 font-mono">
                                        <span>LAT: ${latStr}</span>
                                        <span>LNG: ${lngStr}</span>
                                    </div>
                                </div>
                            </div>
                        `, {
                            autoPanPadding: [80, 80]
                        });

                        marker.on('click', (e) => {
                            L.DomEvent.stopPropagation(e);
                            marker.openPopup();
                        });

                        this.tpsMarkers.push(marker);
                    });
                },

                updateEvents(data) {
                    // Remove existing markers
                    this.eventMarkers.forEach(m => this.map.removeLayer(m));
                    this.eventMarkers = [];

                    // Add new markers
                    data.forEach(item => {
                        if (!item.latitude || !item.longitude) return;

                        // Color: Blue for Events
                        const color = '#3B82F6';
                        
                        const markerIcon = L.divIcon({
                            className: 'custom-div-icon-event',
                            html: `<div style="background-color: ${color}; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg></div>`,
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        });

                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);
                        const latStr = isNaN(lat) ? item.latitude : lat.toFixed(5);
                        const lngStr = isNaN(lng) ? item.longitude : lng.toFixed(5);

                        const marker = L.marker([item.latitude, item.longitude], { 
                            icon: markerIcon,
                            interactive: true
                        }).addTo(this.map);

                        marker.bindPopup(`
                            <div class="font-sans">
                                <div class="px-3 py-2 text-white flex items-center justify-between text-[10px] font-bold tracking-wide" style="background-color: ${color};">
                                    <span>AGENDA PARTAI</span>
                                    <span class="uppercase">${item.status}</span>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-slate-800 text-sm">${item.name}</h4>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        ${item.location}
                                    </p>
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400 font-mono">
                                        <span>LAT: ${latStr}</span>
                                        <span>LNG: ${lngStr}</span>
                                    </div>
                                </div>
                            </div>
                        `, {
                            autoPanPadding: [80, 80]
                        });

                        marker.on('click', (e) => {
                            L.DomEvent.stopPropagation(e);
                            marker.openPopup();
                        });

                        this.eventMarkers.push(marker);
                    });
                },

                updateOffices(data) {
                    // Remove existing markers
                    this.officeMarkers.forEach(m => this.map.removeLayer(m));
                    this.officeMarkers = [];

                    // Add new markers
                    data.forEach(item => {
                        if (!item.latitude || !item.longitude) return;

                        // Color: Gold (#D97706)
                        const color = '#D97706';
                        
                        const markerIcon = L.divIcon({
                            className: 'custom-div-icon-office',
                            html: `<div style="background-color: ${color}; width: 34px; height: 34px; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer; animation: pulse-gold 2s infinite;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg></div>`,
                            iconSize: [34, 34],
                            iconAnchor: [17, 17]
                        });

                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);
                        const latStr = isNaN(lat) ? item.latitude : lat.toFixed(5);
                        const lngStr = isNaN(lng) ? item.longitude : lng.toFixed(5);

                        const marker = L.marker([item.latitude, item.longitude], { 
                            icon: markerIcon,
                            interactive: true
                        }).addTo(this.map);

                        marker.bindPopup(`
                            <div class="font-sans">
                                <div class="px-3 py-2 text-white flex items-center justify-between text-[10px] font-bold tracking-wide" style="background-color: ${color};">
                                    <span>MARKAS / POSKO</span>
                                    <span class="uppercase">${item.type}</span>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-slate-800 text-sm">${item.name}</h4>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        ${item.address}
                                    </p>
                                    <div class="mt-1 text-[10px] text-[#D97706] font-semibold">Radius Geofence: ${item.radius_meters}m</div>
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400 font-mono">
                                        <span>LAT: ${latStr}</span>
                                        <span>LNG: ${lngStr}</span>
                                    </div>
                                </div>
                            </div>
                        `, {
                            autoPanPadding: [80, 80]
                        });

                        marker.on('click', (e) => {
                            L.DomEvent.stopPropagation(e);
                            marker.openPopup();
                        });

                        this.officeMarkers.push(marker);
                    });
                }
            }));
        });
    </script>
</div>
