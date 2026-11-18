<?php
/* Shared city map and live weather panel. Coordinates: Koronadal City Hall. */
?>
<section class="kd-live-section" id="live-city-data" aria-labelledby="kdLiveTitle">
  <div class="kd-live-intro">
    <div><span class="kd-live-eyebrow"><i class="fas fa-satellite-dish" aria-hidden="true"></i> KORONADAL CITY INFORMATION</span>
      <h2 id="kdLiveTitle">Weather & City Map</h2>
      <p>Check local weather conditions and locate the City Government of Koronadal.</p>
    </div>
    <span class="kd-live-locality"><i class="fas fa-location-dot" aria-hidden="true"></i> Koronadal, South Cotabato</span>
  </div>
  <div class="kd-live-grid">
    <article class="kd-live-card kd-weather-card" aria-label="Koronadal weather forecast">
      <div class="kd-live-card-head"><div><span class="kd-card-overline">LIVE WEATHER</span><h3>Koronadal Forecast</h3></div><i class="fas fa-cloud-sun" aria-hidden="true"></i></div>
      <div class="kd-current-weather">
        <div class="kd-temp" data-weather-temp aria-live="polite">--°C</div>
        <div class="kd-weather-description"><strong data-weather-status>Loading weather...</strong><span data-weather-updated>Local time (Asia/Manila)</span></div>
      </div>
      <div class="kd-weather-details">
        <div><i class="fas fa-droplet" aria-hidden="true"></i><span>Humidity</span><strong data-weather-humidity>--%</strong></div>
        <div><i class="fas fa-wind" aria-hidden="true"></i><span>Wind</span><strong data-weather-wind>-- km/h</strong></div>
      </div>
      <div class="kd-forecast-heading">Next five days</div>
      <div class="kd-forecast" data-weather-forecast aria-label="Five-day weather forecast"><span class="kd-weather-loading">Loading forecast...</span></div>
      <p class="kd-weather-source">Weather data: <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a>. Updates when the page loads.</p>
    </article>
    <article class="kd-live-card kd-map-card" aria-label="Koronadal City Hall map">
      <div class="kd-live-card-head"><div><span class="kd-card-overline">INTERACTIVE MAP</span><h3>Koronadal City Hall</h3></div><i class="fas fa-map-location-dot" aria-hidden="true"></i></div>
      <div class="kd-map-frame">
        <iframe title="OpenStreetMap showing Koronadal City Hall" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
          src="https://www.openstreetmap.org/export/embed.html?bbox=124.853%2C6.470%2C124.878%2C6.490&amp;layer=mapnik&amp;marker=6.47965%2C124.86566"></iframe>
      </div>
      <div class="kd-map-foot"><span><i class="fas fa-location-dot" aria-hidden="true"></i> General Santos Drive, Koronadal City, Philippines</span>
        <a href="https://www.openstreetmap.org/?mlat=6.47965&amp;mlon=124.86566#map=16/6.47965/124.86566" target="_blank" rel="noopener noreferrer">Open full map <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
      </div>
    </article>
  </div>
</section>
<script>
(function () {
  const root = document.currentScript.previousElementSibling;
  if (!root || !root.classList.contains('kd-live-section')) return;
  const set = (name, value) => {
    const node = root.querySelector('[data-weather-' + name + ']');
    if (node) node.textContent = value;
  };
  const descriptions = {0:'Clear sky',1:'Mainly clear',2:'Partly cloudy',3:'Overcast',45:'Fog',48:'Depositing fog',51:'Light drizzle',53:'Drizzle',55:'Heavy drizzle',61:'Light rain',63:'Rain',65:'Heavy rain',71:'Light snow',73:'Snow',75:'Heavy snow',80:'Rain showers',81:'Rain showers',82:'Heavy showers',95:'Thunderstorm',96:'Thunderstorm with hail',99:'Thunderstorm with hail'};
  const condition = code => descriptions[code] || 'Weather conditions';
  const labelDay = iso => new Intl.DateTimeFormat('en-PH',{weekday:'short',timeZone:'Asia/Manila'}).format(new Date(iso+'T12:00:00+08:00'));
  const url = 'https://api.open-meteo.com/v1/forecast?latitude=6.47965&longitude=124.86566&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min&timezone=Asia%2FManila&forecast_days=6';
  const abort = new AbortController();
  const timeout = setTimeout(() => abort.abort(), 9000);
  fetch(url,{signal:abort.signal}).then(response => {
    if (!response.ok) throw new Error('Weather service unavailable');
    return response.json();
  }).then(data => {
    const current = data.current || {};
    const daily = data.daily || {};
    if (!Number.isFinite(current.temperature_2m)) throw new Error('Weather data missing');
    set('temp',Math.round(current.temperature_2m)+'°C');
    set('status',condition(current.weather_code));
    set('humidity',Math.round(current.relative_humidity_2m)+'%');
    set('wind',Math.round(current.wind_speed_10m)+' km/h');
    set('updated',new Intl.DateTimeFormat('en-PH',{hour:'numeric',minute:'2-digit',timeZone:'Asia/Manila'}).format(new Date())+' PHT');
    const container = root.querySelector('[data-weather-forecast]');
    if (container) {
      container.replaceChildren();
      (daily.time || []).slice(1,6).forEach((date,i) => {
        const tile=document.createElement('div');
        tile.className='kd-forecast-day';
        const day=document.createElement('strong');
        day.textContent=labelDay(date);
        const icon=document.createElement('i');
        const code=(daily.weather_code || [])[i+1];
        icon.className='fas '+(code >= 61 ? 'fa-cloud-rain' : code >= 2 ? 'fa-cloud-sun' : 'fa-sun');
        icon.setAttribute('aria-hidden','true');
        const temps=document.createElement('span');
        temps.textContent=Math.round(daily.temperature_2m_max[i+1])+'° / '+Math.round(daily.temperature_2m_min[i+1])+'°';
        tile.append(day,icon,temps);
        container.appendChild(tile);
      });
    }
  }).catch(() => {
    set('status','Weather temporarily unavailable');
    set('updated','Please try again later');
    set('temp','--°C');
    const forecast=root.querySelector('[data-weather-forecast]');
    if(forecast) forecast.textContent='Forecast unavailable. Please check the live source.';
  }).finally(() => clearTimeout(timeout));
})();
</script>
