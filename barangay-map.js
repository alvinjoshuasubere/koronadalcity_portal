(function () {
  'use strict';
  const target = document.getElementById('kdBarangayMap');
  const status = document.getElementById('kdBarangayStatus');
  const reload = document.getElementById('kdBarangayReload');
  if (!target || !status) return;
  const city = [124.86566, 6.47965];
  const styleUrl = 'https://tiles.openfreemap.org/styles/positron';
  const names = ['Assumption','Avanceña','Cacub','Caloocan','Carpenter Hill','Concepcion','Esperanza','General Paulino Santos','Mabini','Magsaysay','Mambucal','Morales','Namnama','New Pangasinan','Paraiso','Rotonda','San Isidro','San Jose','San Roque','Santa Cruz','Santo Niño','Saravia','Topland','Zone 1','Zone 2','Zone 3','Zone 4'];
  const cacheKey = 'koronadal-osm-boundaries-city-only-v3';
  let map, abortController, hoverPopup;
  const setStatus = message => {status.textContent = message;};
  const normalized = name => String(name || '').trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .replace(/^barangay\s+/,'').replace(/\bzone\s+(?:i{1,3}|iv)\b/g, match => {
      const roman = match.split(/\s+/)[1];
      return 'zone ' + ({i:1,ii:2,iii:3,iv:4}[roman] || roman);
    }).replace(/\s+/g,' ');
  const known = new Set(names.map(normalized));
  function validFeature(feature) {
    if (!feature || !feature.geometry || !['Polygon','MultiPolygon'].includes(feature.geometry.type)) return false;
    const p = feature.properties || {};
    const name = p.name || p['name:en'] || '';
    if (!known.has(normalized(name))) return false;
    const coords = feature.geometry.coordinates;
    let vertex = null;
    const walk = points => {if (vertex || !Array.isArray(points)) return; if (typeof points[0] === 'number') vertex=points; else points.forEach(walk);};
    walk(coords);
    return !!vertex && vertex[0]>124.65 && vertex[0]<125.05 && vertex[1]>6.25 && vertex[1]<6.70;
  }
  function paint(collection) {
    if (!map || !map.isStyleLoaded()) return;
    const source = map.getSource('koronadal-barangays');
    if (source) source.setData(collection);
    else {
      map.addSource('koronadal-barangays', {type:'geojson',data:collection});
      map.addLayer({id:'koronadal-barangay-fill',type:'fill',source:'koronadal-barangays',paint:{'fill-color':'#3b82f6','fill-opacity':0.12}});
      map.addLayer({id:'koronadal-barangay-lines',type:'line',source:'koronadal-barangays',paint:{'line-color':'#1751a5','line-width':['interpolate',['linear'],['zoom'],10,2,14,3.8],'line-opacity':1}});
      map.addLayer({id:'koronadal-barangay-hover',type:'fill',source:'koronadal-barangays',
        paint:{'fill-color':'#2563eb','fill-opacity':['case',['boolean',['feature-state','hover'],false],0.24,0]}});
      map.addLayer({id:'koronadal-barangay-names',type:'symbol',source:'koronadal-barangays',layout:{'text-field':['get','name'],'text-size':11,'text-font':['Noto Sans Regular'],'text-max-width':10},paint:{'text-color':'#153e75','text-halo-color':'#fff','text-halo-width':1.7}});
    }
    const bounds = new maplibregl.LngLatBounds();
    function visit(coords) {
      if (!Array.isArray(coords)) return;
      if (typeof coords[0] === 'number' && typeof coords[1] === 'number') {
        if (coords[0] > 124.6 && coords[0] < 125.1 && coords[1] > 6.2 && coords[1] < 6.75) bounds.extend(coords);
      } else coords.forEach(visit);
    }
    collection.features.forEach(feature => visit(feature.geometry.coordinates));
    if (!bounds.isEmpty()) map.fitBounds(bounds,{padding:40,maxZoom:12.7,duration:650});
    const found = new Set(collection.features.map(feature => normalized(feature.properties.name))).size;
    setStatus(found + ' of 27 Koronadal barangay boundaries mapped. Hover to see a name or tap on mobile.' + (found < 27 ? ' Unmapped boundaries are not drawn.' : ''));
  }
  async function load(force) {
    if (!map || !map.isStyleLoaded()) return;
    if (abortController) abortController.abort();
    if (!force) {
      try {
        const item = JSON.parse(sessionStorage.getItem(cacheKey));
        if (item && Date.now() - item.time < 12 * 60 * 60 * 1000 && item.data && item.data.features.length) {paint(item.data);return;}
      } catch(e) {}
    }
    setStatus('Loading available barangay boundary lines from OpenStreetMap...');
    abortController = new AbortController();
    const timeout = setTimeout(() => abortController.abort(), 24000);
    // A single area-based request; never perform 27 independent geocoding requests.
    const query = '[out:json][timeout:20];area["boundary"="administrative"]["name"~"^(City of )?Koronadal$",i]->.koronadal;(relation(area.koronadal)["boundary"="administrative"]["admin_level"="10"];);out body;>;out skel qt;';
    try {
      if (typeof window.osmtogeojson !== 'function') throw new Error('Boundary conversion library unavailable');
      const response = await fetch('https://overpass.kumi.systems/api/interpreter', {
        method:'POST',body:new URLSearchParams({data:query}),signal:abortController.signal
      });
      if (!response.ok) throw new Error('Boundary service unavailable');
      const data = await response.json();
      const converted = window.osmtogeojson(data);
      const seen = new Set();
      const features = (converted.features || []).filter(validFeature).filter(f => {
        const key=normalized(f.properties.name);
        if (seen.has(key)) return false;
        seen.add(key);return true;
      }).map((f,i) => ({
        type:'Feature',id:i+1,properties:{name:f.properties.name},geometry:f.geometry
      }));
      if (!features.length) throw new Error('No mapped barangay boundaries available within Koronadal');
      const collection = {type:'FeatureCollection',features};
      try {sessionStorage.setItem(cacheKey,JSON.stringify({time:Date.now(),data:collection}));} catch(e) {}
      paint(collection);
    } catch(e) {
      if (e.name === 'AbortError') setStatus('Boundary request timed out. Select Refresh boundaries to try again.');
      else setStatus('Barangay boundary lines are currently unavailable from OpenStreetMap. The base map remains available. Select Refresh boundaries to retry.');
    } finally {clearTimeout(timeout);}
  }
  function init(attempt=0) {
    if (!window.maplibregl || !window.osmtogeojson) {
      if (attempt < 40) {setTimeout(() => init(attempt+1),200);return;}
      setStatus('Map resources did not load. Check your connection and reload the page.');return;
    }
    try {
      target.replaceChildren();
      map = new maplibregl.Map({container:target,style:styleUrl,center:city,zoom:11.3,attributionControl:false});
      map.addControl(new maplibregl.NavigationControl({showCompass:false}),'top-right');
      map.addControl(new maplibregl.AttributionControl({compact:true}),'bottom-right');
      map.on('load',()=>load(false));
      let hoveredId = null;
      hoverPopup = new maplibregl.Popup({closeButton:false,closeOnClick:false,offset:14,className:'kd-barangay-tooltip'});
      function hoverBoundary(event) {
        const feature = event.features && event.features[0];
        if (!feature) return;
        if (hoveredId !== null && map.getSource('koronadal-barangays'))
          map.setFeatureState({source:'koronadal-barangays',id:hoveredId},{hover:false});
        hoveredId = feature.id;
        if (hoveredId !== null && hoveredId !== undefined)
          map.setFeatureState({source:'koronadal-barangays',id:hoveredId},{hover:true});
        map.getCanvas().style.cursor = 'pointer';
        hoverPopup.setLngLat(event.lngLat).setText('Barangay ' + feature.properties.name).addTo(map);
      }
      map.on('mousemove','koronadal-barangay-fill',hoverBoundary);
      map.on('mouseleave','koronadal-barangay-fill',() => {
        if (hoveredId !== null && map.getSource('koronadal-barangays'))
          map.setFeatureState({source:'koronadal-barangays',id:hoveredId},{hover:false});
        hoveredId = null;
        map.getCanvas().style.cursor = '';
        hoverPopup.remove();
      });
      map.on('click','koronadal-barangay-fill',event => {
        const feature=event.features && event.features[0];
        if (feature) new maplibregl.Popup({offset:14}).setLngLat(event.lngLat)
          .setText('Barangay '+feature.properties.name).addTo(map);
      });
      if (reload) reload.addEventListener('click',()=>load(true));
    } catch(e) {setStatus('Unable to initialize the map. Please refresh the page.');}
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded',()=>init());
  else init();
})();
