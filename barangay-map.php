<?php /* Barangay names from the City Government of Koronadal 27-barangay directory. */ ?>
<section class="kd-barangay-section" aria-labelledby="kdBarangayMapTitle">
  <div class="kd-barangay-heading">
    <div><span class="kd-live-eyebrow">27 BARANGAYS OF KORONADAL</span>
      <h2 id="kdBarangayMapTitle">Explore the Barangays</h2>
      <p>Only Koronadal barangay names appear on this map. Select one or more barangays to display their available boundary outlines.</p>
    </div>
    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener noreferrer">Official barangay directory <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
  </div>
  <div class="kd-barangay-all-layout">
    <div class="kd-barangay-all-toolbar">
      <div><strong>Koronadal Zoning Map</strong><span id="kdZoneCount" class="kd-zone-count">Loading mapped zones...</span><span id="kdBarangayStatus" role="status" aria-live="polite">Loading available barangay boundaries...</span></div>
      <button id="kdBarangayShowAll" class="kd-barangay-reset" type="button"><i class="fas fa-expand" aria-hidden="true"></i> View all Koronadal</button>
      <button id="kdBarangayReload" class="kd-barangay-reset" type="button"><i class="fas fa-rotate" aria-hidden="true"></i> Refresh boundaries</button>
    </div>
    <div class="kd-barangay-filter" aria-label="Choose barangay boundaries">
      <div class="kd-barangay-filter-head">
        <div><strong>Filter Barangay Boundaries</strong><span id="kdFilterCount">All 27 selected</span></div>
        <div class="kd-barangay-filter-actions">
          <button type="button" id="kdSelectAllBarangays" class="kd-filter-action">Select All 27</button>
          <button type="button" id="kdClearBarangays" class="kd-filter-action">Clear Selection</button>
        </div>
      </div>
      <label class="kd-barangay-search-label" for="kdBarangayFilterSearch">Find a barangay</label>
      <input type="search" id="kdBarangayFilterSearch" class="kd-barangay-filter-search" placeholder="Search Koronadal barangays" autocomplete="off">
      <div id="kdBarangayFilterList" class="kd-barangay-filter-list" role="group" aria-label="Select one or more Koronadal barangays">
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Assumption" checked><span>Assumption</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Avanceña" checked><span>Avanceña</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Cacub" checked><span>Cacub</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Caloocan" checked><span>Caloocan</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Carpenter Hill" checked><span>Carpenter Hill</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Concepcion" checked><span>Concepcion</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Esperanza" checked><span>Esperanza</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="General Paulino Santos" checked><span>General Paulino Santos</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Mabini" checked><span>Mabini</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Magsaysay" checked><span>Magsaysay</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Mambucal" checked><span>Mambucal</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Morales" checked><span>Morales</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Namnama" checked><span>Namnama</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="New Pangasinan" checked><span>New Pangasinan</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Paraiso" checked><span>Paraiso</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Rotonda" checked><span>Rotonda</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="San Isidro" checked><span>San Isidro</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="San Jose" checked><span>San Jose</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="San Roque" checked><span>San Roque</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Santa Cruz" checked><span>Santa Cruz</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Santo Niño" checked><span>Santo Niño</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Saravia" checked><span>Saravia</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Topland" checked><span>Topland</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Zone 1" checked><span>Zone 1</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Zone 2" checked><span>Zone 2</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Zone 3" checked><span>Zone 3</span></label>
        <label class="kd-barangay-filter-item"><input type="checkbox" value="Zone 4" checked><span>Zone 4</span></label>
      </div>
      <p class="kd-barangay-filter-help">Choose one, several, or all barangays to display their available boundaries. Click a mapped barangay to see its name.</p>
    </div>
    <div class="kd-barangay-canvas-wrap">
      <div id="kdBarangayMap" class="kd-barangay-map" role="region" aria-label="Interactive map showing available Koronadal barangay boundary lines">
        <div class="kd-barangay-map-loading">Loading OpenFreeMap...</div>
      </div>
      <div class="kd-zone-legend" aria-label="Map legend"><span><i class="kd-legend-city"></i> Barangay boundary</span><span><i class="kd-legend-barangay"></i> Selected barangays</span><span><i class="kd-legend-selected"></i> Hover or tap highlight</span></div>
      <div class="kd-barangay-attribution">Map tiles by <a href="https://openfreemap.org/" target="_blank" rel="noopener noreferrer">OpenFreeMap</a>. Boundary data: <a href="https://portal.georisk.gov.ph/arcgis/rest/services/PSA/Barangay/MapServer/4" target="_blank" rel="noopener noreferrer">Philippine Statistics Authority (GeoRisk)</a>, with OpenStreetMap fallback. PSA polygons are indicative 2016 boundaries, not official cadastral surveys. Missing mapped boundaries are not estimated.</div>
    </div>
  </div>
</section>
<link href="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.css" rel="stylesheet">
<script src="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.js" defer></script>
<script src="https://unpkg.com/osmtogeojson@3.0.0-beta.5/osmtogeojson.js" defer></script>
<script defer src="barangay-map.js?v=20261119-14"></script>
