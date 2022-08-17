<?php
use MapasCulturais\i;
$this->import('mc-icon')
?>
<div class="mc-map">
    <l-map 
        ref="map" 
        :zoom="defaultZoom" 
        :max-zoom="maxZoom" 
        :min-zoom="minZoom"
        :center="center" 
        zoom-animation 
        fade-animation 
        use-global-leaflet
        @ready="handleMapSetup()">
        <l-tile-layer :url="tileServer"></l-tile-layer>
        <slot></slot>

        <div ref="agent1" class="agent__background mc-map-marker">
            <mc-icon name="agent-1"></mc-icon>
        </div>
        <div ref="agent2" class="agent__background mc-map-marker">
            <mc-icon name="agent-2"></mc-icon>
        </div>
        <div ref="space" class="space__background mc-map-marker">
            <mc-icon name="space"></mc-icon>
        </div>
        <div ref="event" class="event__background mc-map-marker">
            <mc-icon name="event"></mc-icon>
        </div>        
    </l-map>
</div>

