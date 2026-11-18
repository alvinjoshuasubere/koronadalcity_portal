(function () {
  'use strict';
  const select = document.getElementById('kdBarangaySelect');
  const target = document.getElementById('kdBarangayMap');
  const status = document.getElementById('kdBarangayStatus');
  const reset = document.getElementById('kdBarangayReset');
  const choices = document.getElementById('kdBarangayChoices');
  if (!select || !target || !status || !reset) return;
  const city = [124.86566, 6.47965];
  const cityZoom = 11.5;
  const styleUrl = 'https://tiles.openfreemap.org/styles/positron';
  let map, marker, requestId = 0, controller = null;
  const cache = new Map();
  function setStatus(message) { status.textContent = message; }
  function syncChoices() {
    if (!choices) return;
    choices.querySelectorAll('button[data-barangay]').forEach(button => {
      const active = button.dataset.barangay === select.value;
      button.classList.toggle('active', active);
      button.setAttribute('aria-pressed', String(active));
    });
  }
  if (choices) {
    Array.from(select.options).slice(1).forEach(option => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'kd-barangay-chip';
      button.dataset.barangay = option.value;
      button.textContent = option.textContent;
      button.setAttribute('aria-pressed', 'false');
      button.addEventListener('click', () => {
        select.value = option.value;
        syncChoices();
        choose();
      });
      choices.appendChild(button);
    });
  }
  function resetView() {
    requestId++;
    if (controller) controller.abort();
    controller = null;
    select.value = '';
    syncChoices();
    if (marker) { marker.remove(); marker = null; }
    if (map && map.getLayer('barangay-outline')) map.removeLayer('barangay-outline');
    if (map && map.getLayer('barangay-area')) map.removeLayer('barangay-area');
    if (map && map.getSource('barangay-boundary')) map.removeSource('barangay-boundary');
    if (map) map.flyTo({ center: city, zoom: cityZoom, essential: true });
    setStatus('Showing the City of Koronadal. Select a barangay to explore its map.');
  }
  function removeGeometry() {
    if (marker) { marker.remove(); marker = null; }
    for (const id of ['barangay-outline','barangay-area']) if (map.getLayer(id)) map.removeLayer(id);
    if (map.getSource('barangay-boundary')) map.removeSource('barangay-boundary');
  }
  function showResult(name, data) {
    removeGeometry();
    const lon = parseFloat(data.lon), lat = parseFloat(data.lat);
    if (!Number.isFinite(lon) || !Number.isFinite(lat) || lon < 124.65 || lon > 125.05 || lat < 6.25 || lat > 6.70) {
      throw new Error('The location could not be verified inside Koronadal.');
    }
    marker = new maplibregl.Marker({color:'#2563eb'}).setLngLat([lon,lat])
      .setPopup(new maplibregl.Popup({offset:22}).setText('Barangay '+name+', Koronadal City'))
      .addTo(map);
    const boundary = data.geojson;
    if (boundary && ['Polygon','MultiPolygon'].includes(boundary.type)) {
      map.addSource('barangay-boundary',{type:'geojson',data:{type:'Feature',properties:{name:name},geometry:boundary}});
      map.addLayer({id:'barangay-area',type:'fill',source:'barangay-boundary',paint:{'fill-color':'#2563eb','fill-opacity':0.10}});
      map.addLayer({id:'barangay-outline',type:'line',source:'barangay-boundary',paint:{'line-color':'#2563eb','line-width':2.5}});
      const bbox = data.boundingbox;
      if (Array.isArray(bbox) && bbox.length === 4) {
        const south=Number(bbox[0]), north=Number(bbox[1]), west=Number(bbox[2]), east=Number(bbox[3]);
        if ([south,north,west,east].every(Number.isFinite) && north > south && east > west) {
          map.fitBounds([[west,south],[east,north]],{padding:55,maxZoom:15,duration:700});
        } else { map.flyTo({center:[lon,lat],zoom:14}); }
      } else { map.flyTo({center:[lon,lat],zoom:14}); }
      setStatus('Barangay '+name+' selected. The shaded outline is from OpenStreetMap, not an official surveyed boundary.');
    } else {
      map.flyTo({center:[lon,lat],zoom:14});
      setStatus('Barangay '+name+' selected. A reference location is shown because a mapped boundary is unavailable.');
    }
  }
  async function choose() {
    const name=select.value;
    syncChoices();
    if (!name) {resetView(); return;}
    const id=++requestId;
    if (controller) controller.abort();
    controller=new AbortController();
    if (!map) {
      setStatus('OpenFreeMap is loading. Please select the barangay again shortly.');
      return;
    }
    if (!map.isStyleLoaded()) {
      setStatus('Loading map style before locating Barangay '+name+'...');
      map.once('load', () => { if (select.value === name) choose(); });
      return;
    }
    setStatus('Finding Barangay '+name+' in OpenStreetMap...');
    if (cache.has(name)) {
      try { showResult(name,cache.get(name)); } catch(e) {setStatus(e.message);}
      return;
    }
    // User-initiated searches only, one at a time. Do not bulk-geocode the full list.
    const query=name.replace(/^Zone ([1-4])$/,(_,n)=>'Zone '+n)+', Koronadal, South Cotabato, Philippines';
    const params=new URLSearchParams({
      q:query,format:'jsonv2',limit:'3',countrycodes:'ph',
      polygon_geojson:'1',addressdetails:'1',
      viewbox:'124.65,6.70,125.05,6.25',bounded:'1'
    });
    try {
      const response=await fetch('https://nominatim.openstreetmap.org/search?'+params,{signal:controller.signal,headers:{'Accept':'application/json'}});
      if (!response.ok) throw Error('Map search is temporarily unavailable.');
      const list=await response.json();
      if (id!==requestId) return;
      const match=list.find(item=> {
        const text=String(item.display_name||'').toLowerCase();
        return text.includes('koronadal') && Number(item.lon)>124.65 && Number(item.lon)<125.05 &&
          Number(item.lat)>6.25 && Number(item.lat)<6.70;
      });
      if (!match) {
        removeGeometry();
        map.flyTo({center:city,zoom:cityZoom});
        setStatus('No verified map location for Barangay '+name+' was found. The city view remains visible.');
        return;
      }
      cache.set(name,match);
      showResult(name,match);
    } catch(error) {
      if (error.name==='AbortError') return;
      if (id!==requestId) return;
      removeGeometry();
      setStatus('Barangay map location unavailable. Please try another barangay or use the official directory.');
    }
  }
  function init(attempt=0) {
    if (!window.maplibregl) {
      if (attempt<35) {setTimeout(()=>init(attempt+1),200);return;}
      setStatus('The interactive map could not load. Check your connection and refresh.');
      return;
    }
    try {
      target.replaceChildren();
      map=new maplibregl.Map({container:target,style:styleUrl,center:city,zoom:cityZoom,attributionControl:false});
      map.addControl(new maplibregl.NavigationControl({showCompass:false}),'top-right');
      map.addControl(new maplibregl.AttributionControl({compact:true}),'bottom-right');
      map.on('error',()=> {if(!map.loaded()) setStatus('The map tiles could not load. Please check your connection.');});
      select.addEventListener('change',choose);
      reset.addEventListener('click',resetView);
    } catch(e) {setStatus('Unable to initialize OpenFreeMap. Please refresh the page.');}
  }
  if (document.readyState==='loading') document.addEventListener('DOMContentLoaded',()=>init());
  else init();
})();
