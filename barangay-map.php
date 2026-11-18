<?php /* Barangay names from the City Government of Koronadal 27-barangay directory. */ ?>
<section class="kd-barangay-section" aria-labelledby="kdBarangayMapTitle">
  <div class="kd-barangay-heading">
    <div><span class="kd-live-eyebrow">27 BARANGAYS OF KORONADAL</span>
      <h2 id="kdBarangayMapTitle">Explore the Barangays</h2>
      <p>See mapped barangay boundaries together on one interactive map. No barangay selection is needed.</p>
    </div>
    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener noreferrer">Official barangay directory <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
  </div>
  <div class="kd-barangay-all-layout">
    <div class="kd-barangay-all-toolbar">
      <div><strong>Koronadal Barangay Boundaries</strong><span id="kdBarangayStatus" role="status" aria-live="polite">Loading available barangay boundaries...</span></div>
      <button id="kdBarangayReload" class="kd-barangay-reset" type="button"><i class="fas fa-rotate" aria-hidden="true"></i> Refresh boundaries</button>
    </div>
    <div class="kd-barangay-canvas-wrap">
      <div id="kdBarangayMap" class="kd-barangay-map" role="region" aria-label="Interactive map showing available Koronadal barangay boundary lines">
        <div class="kd-barangay-map-loading">Loading OpenFreeMap...</div>
      </div>
      <div class="kd-barangay-attribution">Map tiles by <a href="https://openfreemap.org/" target="_blank" rel="noopener noreferrer">OpenFreeMap</a>. Boundary data © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap contributors</a>. Boundaries are indicative and not official cadastral surveys. Missing mapped boundaries are not estimated.</div>
    </div>
  </div>
</section>
<link href="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.css" rel="stylesheet">
<script src="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.js" defer></script>
<script src="https://unpkg.com/osmtogeojson@3.0.0-beta.5/osmtogeojson.js" defer></script>
<script defer src="barangay-map.js?v=20261118-6"></script>
