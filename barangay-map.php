<?php /* Barangay names from the City Government of Koronadal 27-barangay directory. */ ?>
<section class="kd-barangay-section" aria-labelledby="kdBarangayMapTitle">
  <div class="kd-barangay-heading">
    <div><span class="kd-live-eyebrow">27 BARANGAYS OF KORONADAL</span>
      <h2 id="kdBarangayMapTitle">Explore the Barangays</h2>
      <p>Choose a barangay to locate it on a separate OpenFreeMap view. Where geographic boundary data is available, the map will show its outline.</p>
    </div>
    <a href="https://koronadal.gov.ph/27-barangay/" target="_blank" rel="noopener noreferrer">Official barangay directory <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
  </div>
  <div class="kd-barangay-layout">
    <div class="kd-barangay-sidebar">
      <label for="kdBarangaySelect">Select barangay</label>
      <select id="kdBarangaySelect" class="kd-barangay-select">
        <option value="">All of Koronadal</option>
        <option value="Assumption">Assumption</option>
            <option value="Avanceña">Avanceña</option>
            <option value="Cacub">Cacub</option>
            <option value="Caloocan">Caloocan</option>
            <option value="Carpenter Hill">Carpenter Hill</option>
            <option value="Concepcion">Concepcion</option>
            <option value="Esperanza">Esperanza</option>
            <option value="General Paulino Santos">General Paulino Santos</option>
            <option value="Mabini">Mabini</option>
            <option value="Magsaysay">Magsaysay</option>
            <option value="Mambucal">Mambucal</option>
            <option value="Morales">Morales</option>
            <option value="Namnama">Namnama</option>
            <option value="New Pangasinan">New Pangasinan</option>
            <option value="Paraiso">Paraiso</option>
            <option value="Rotonda">Rotonda</option>
            <option value="San Isidro">San Isidro</option>
            <option value="San Jose">San Jose</option>
            <option value="San Roque">San Roque</option>
            <option value="Santa Cruz">Santa Cruz</option>
            <option value="Santo Niño">Santo Niño</option>
            <option value="Saravia">Saravia</option>
            <option value="Topland">Topland</option>
            <option value="Zone 1">Zone 1</option>
            <option value="Zone 2">Zone 2</option>
            <option value="Zone 3">Zone 3</option>
            <option value="Zone 4">Zone 4</option>
      </select>
      <div id="kdBarangayStatus" class="kd-barangay-status" role="status" aria-live="polite">Showing the City of Koronadal. Select a barangay to explore its map.</div>
      <button id="kdBarangayReset" class="kd-barangay-reset" type="button"><i class="fas fa-rotate-left" aria-hidden="true"></i> Reset city view</button>
      <p class="kd-barangay-disclaimer">Locations and boundaries depend on OpenStreetMap coverage and are for reference only, not official cadastral or administrative surveys.</p>
    </div>
    <div class="kd-barangay-canvas-wrap">
      <div id="kdBarangayMap" class="kd-barangay-map" role="region" aria-label="Interactive OpenFreeMap of Koronadal barangays">
        <div class="kd-barangay-map-loading">Loading OpenFreeMap...</div>
      </div>
      <div class="kd-barangay-attribution">Map tiles by <a href="https://openfreemap.org/" target="_blank" rel="noopener noreferrer">OpenFreeMap</a>. Geographic data © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap contributors</a>.</div>
    </div>
  </div>
</section>
<link href="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.css" rel="stylesheet">
<script src="https://unpkg.com/maplibre-gl@5.8.0/dist/maplibre-gl.js" defer></script>
<script defer src="barangay-map.js?v=20261118-1"></script>
