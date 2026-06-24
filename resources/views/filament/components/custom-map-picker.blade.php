<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="customMapPicker($wire, {{ $getMapConfig() }}, {{ $field->isDisabled() ? 'true' : 'false' }})"
            x-init="async () => {
            do {
                await (new Promise(resolve => setTimeout(resolve, 100)));
            } while (!$refs.map);
            attach($refs.map);
        }"
            wire:ignore
    >
        <div
            x-ref="map"
            class="w-full" style="min-height: 30vh; z-index: 1; position: relative; {{ $getExtraStyle() }}">
        </div>
        <input type="text" id="{{ $getStatePath() }}_fmrest" style="display:none"/>
    </div>
    <script>
        if (typeof window.customMapPicker === 'undefined') {
            window.customMapPicker = function($wire, config, isDisabled) {
                if (isDisabled) {
                    config.draggable = true;
                    config.showMarker = true;
                    config.clickable = false;
                    config.showMyLocationButton = [false, false, 5000];
                    if (config.geoMan) {
                        config.geoMan.show = false;
                    }
                }
                
                // Get the original mapPicker object
                const base = window.mapPicker($wire, config);
                
                if (isDisabled) {
                    const originalCreateMap = base.createMap;
                    base.createMap = function(el) {
                        // Temporarily flag clickable as true to bypass leaflet 'move' / 'moveend' listeners in original mapPicker
                        config.clickable = true;
                        
                        originalCreateMap.call(this, el);
                        
                        config.clickable = false;
                        
                        // Disable the click listener that was added because of config.clickable = true
                        if (this.map) {
                            this.map.off('click');
                        }
                    };
                }
                
                return base;
            };
        }
    </script>
</x-dynamic-component>
