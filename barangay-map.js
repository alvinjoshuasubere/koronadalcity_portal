(function () {
  'use strict';
  const el = document.getElementById('kdBarangayMap');
  const status = document.getElementById('kdBarangayStatus');
  const refresh = document.getElementById('kdBarangayReload');
  const count = document.getElementById('kdZoneCount');
  const showAll = document.getElementById('kdBarangayShowAll');
  if (!el || !status || !window) return;

  const center = [124.86566, 6.47965];
  const cityBounds = [[124.765, 6.382], [125.009, 6.540]];
  const names = ['Assumption','Avanceña','Cacub','Caloocan','Carpenter Hill','Concepcion','Esperanza','General Paulino Santos','Mabini','Magsaysay','Mambucal','Morales','Namnama','New Pangasinan','Paraiso','Rotonda','San Isidro','San Jose','San Roque','Santa Cruz','Santo Niño','Saravia','Topland','Zone 1','Zone 2','Zone 3','Zone 4'];
  const normalized = value => String(value || '').toLowerCase().normalize('NFD')
    .replace(/[\u0300-\u036f]/g,'').replace(/^barangay\s+/,'').trim()
    .replace(/\bzone\s+(iv|iii|ii|i)\b/g, (value, roman) =>
      'zone '+({i:1,ii:2,iii:3,iv:4}[roman]))
    .replace(/\s+/g,' ');
  const allowed = new Set(names.map(normalized));
  const cacheKey = 'kd-koronadal-zoning-v1';
  const empty = {type:'FeatureCollection',features:[]};
  const areaQuery = '[out:json][timeout:25];relation["boundary"="administrative"]["admin_level"="6"]["name"~"Koronadal",i](6.35,124.72,6.58,125.04)->.city;.city map_to_area->.cityArea;(.city;relation["boundary"="administrative"]["admin_level"="10"](area.cityArea););out body;>;out skel qt;';
  let map, pending, hoveredId = null, tooltip, cityGeometry = null, latest = null;
  const setStatus = message => { status.textContent = message; };
  function isPolygon(feature) {
    return feature && feature.geometry &&
      ['Polygon','MultiPolygon'].includes(feature.geometry.type);
  }
  function withinKoronadal(feature) {
    if (!isPolygon(feature)) return false;
    // The server-side Overpass area restricts relations to Koronadal.
    // This additional geographic check rejects unrelated search results.
    let found = false;
    const visit = coords => {
      if (found || !Array.isArray(coords)) return;
      if (typeof coords[0] === 'number' && typeof coords[1] === 'number') {
        if (coords[0] >= 124.70 && coords[0] <= 125.04 &&
            coords[1] >= 6.34 && coords[1] <= 6.60) found = true;
      } else coords.forEach(visit);
    };
    visit(feature.geometry.coordinates);
    return found;
  }
  function measureBounds(features) {
    const bounds = new maplibregl.LngLatBounds();
    features.forEach(feature => {
      const scan = coords => {
        if (!Array.isArray(coords)) return;
        if (typeof coords[0] === 'number') {
          if (Number.isFinite(coords[0]) && Number.isFinite(coords[1]) &&
              coords[0] >= 124.70 && coords[0] <= 125.04 &&
              coords[1] >= 6.34 && coords[1] <= 6.60) bounds.extend(coords);
        } else coords.forEach(scan);
      };
      scan(feature.geometry.coordinates);
    });
    return bounds;
  }
  function fitCity() {
    if (!map) return;
    const bounds = cityGeometry ? measureBounds([cityGeometry]) : null;
    map.fitBounds(bounds && !bounds.isEmpty() ? bounds : cityBounds,{
      padding:window.innerWidth < 650 ? 22 : 55,maxZoom:12.5,duration:650
    });
  }
  function ensureSource(id, data) {
    if (map.getSource(id)) map.getSource(id).setData(data);
    else map.addSource(id,{type:'geojson',data});
  }
  function draw(payload) {
    if (!map || !map.isStyleLoaded()) return;
    latest = payload;
    cityGeometry = payload.city.features[0] || null;
    ensureSource('kd-city-zone',payload.city);
    ensureSource('kd-barangay-zone',payload.barangays);

    if (!map.getLayer('kd-city-fill')) {
      map.addLayer({id:'kd-city-fill',type:'fill',source:'kd-city-zone',
        paint:{'fill-color':'#0b7cb8','fill-opacity':0.12}});
      map.addLayer({id:'kd-brgy-fill',type:'fill',source:'kd-barangay-zone',
        paint:{'fill-color':['match',['%', ['get','zoneIndex'], 4],0,'#68c7eb',1,'#91bff6',2,'#b3d7ed','#78c2cf'],'fill-opacity':0.28}});
      map.addLayer({id:'kd-brgy-hover',type:'fill',source:'kd-barangay-zone',
        paint:{'fill-color':'#1d4ed8','fill-opacity':['case',['boolean',['feature-state','hover'],false],0.33,0]}});
      map.addLayer({id:'kd-brgy-line',type:'line',source:'kd-barangay-zone',
        paint:{'line-color':'#236ab8','line-width':['interpolate',['linear'],['zoom'],10,1.5,14,3],'line-opacity':1}});
      map.addLayer({id:'kd-city-outline',type:'line',source:'kd-city-zone',
        paint:{'line-color':'#073d80','line-width':['interpolate',['linear'],['zoom'],10,3,14,5],'line-opacity':1}});
      map.addLayer({id:'kd-brgy-label',type:'symbol',source:'kd-barangay-zone',
        layout:{'text-field':['get','name'],'text-font':['Noto Sans Regular'],
          'text-size':['interpolate',['linear'],['zoom'],10,10,14,13],
          'text-allow-overlap':false,'text-max-width':10},
        paint:{'text-color':'#123b69','text-halo-color':'#fff','text-halo-width':1.8}});
    }
    fitCity();
    const mapped = payload.barangays.features.length;
    if (count) count.textContent = mapped + ' / 27 barangays mapped';
    setStatus('Koronadal City highlighted with '+mapped+' mapped barangay zones. Hover to view names, or tap on mobile.' +
      (mapped < 27 ? ' Unavailable boundaries are not estimated.' : ''));
  }
  function decode(data) {
    const geo = window.osmtogeojson(data);
    const relations = (geo.features || []).filter(feature =>
      isPolygon(feature) && String(feature.id || '').startsWith('relation/'));
    const cityFeature = relations.find(feature => {
      const p = feature.properties || {};
      return String(p.admin_level) === '6' && normalized(p.name).includes('koronadal') && withinKoronadal(feature);
    });
    if (!cityFeature) throw Error('Koronadal city boundary unavailable');
    const seen = new Set();
    const barangays = relations.filter(feature => {
      const p = feature.properties || {};
      const name = normalized(p.name);
      if (String(p.admin_level) !== '10' || !allowed.has(name) || seen.has(name) ||
          !withinKoronadal(feature)) return false;
      seen.add(name);
      return true;
    }).map((feature,i)=>({
      type:'Feature',id:i+1,geometry:feature.geometry,
      properties:{name:feature.properties.name,zoneIndex:i}
    }));
    return {
      city:{type:'FeatureCollection',features:[{
        type:'Feature',id:1000,geometry:cityFeature.geometry,
        properties:{name:'City of Koronadal'}
      }]},
      barangays:{type:'FeatureCollection',features:barangays}
    };
  }
  async function load(force) {
    if (!map || !map.isStyleLoaded()) return;
    if (pending) pending.abort();
    if (!force) {
      try {
        const cached = JSON.parse(sessionStorage.getItem(cacheKey));
        if (cached && Date.now()-cached.at < 6*60*60*1000 && cached.data) {
          draw(cached.data);return;
        }
      } catch (_) {}
    }
    setStatus('Loading Koronadal zoning boundaries from OpenStreetMap...');
    pending = new AbortController();
    const signal = pending.signal;
    const timer = setTimeout(()=>pending.abort(),30000);
    try {
      if (typeof window.osmtogeojson !== 'function') throw Error('Mapping library unavailable');
      const response = await fetch('https://overpass.kumi.systems/api/interpreter',{
        method:'POST',body:new URLSearchParams({data:areaQuery}),signal
      });
      if (!response.ok) throw Error('OpenStreetMap boundary service unavailable');
      const parsed = decode(await response.json());
      if (signal.aborted) return;
      draw(parsed);
      try {sessionStorage.setItem(cacheKey,JSON.stringify({at:Date.now(),data:parsed}));} catch (_) {}
    } catch(error) {
      if (!signal.aborted) setStatus('Koronadal boundary data is unavailable right now. Refresh to try again. The OpenFreeMap base map remains visible.');
      else setStatus('The boundary request timed out. Refresh boundaries to try again.');
    } finally { clearTimeout(timer); }
  }
  function init(attempt=0) {
    if (!window.maplibregl || !window.osmtogeojson) {
      if (attempt < 45) {setTimeout(()=>init(attempt+1),200);return;}
      setStatus('The map libraries did not load. Check your connection and refresh.');return;
    }
    try {
      el.replaceChildren();
      map=new maplibregl.Map({container:el,style:'https://tiles.openfreemap.org/styles/positron',
        center,zoom:11.3,attributionControl:false,maxBounds:[[124.60,6.20],[125.16,6.76]]});
      map.addControl(new maplibregl.NavigationControl({showCompass:false}),'top-right');
      map.addControl(new maplibregl.AttributionControl({compact:true}),'bottom-right');
      map.on('load',()=>load(false));
      tooltip=new maplibregl.Popup({closeButton:false,closeOnClick:false,offset:12,className:'kd-barangay-tooltip'});
      map.on('mousemove','kd-brgy-fill',event=>{
        const feature=event.features && event.features[0];
        if (!feature) return;
        if (hoveredId !== null && map.getSource('kd-barangay-zone'))
          map.setFeatureState({source:'kd-barangay-zone',id:hoveredId},{hover:false});
        hoveredId=feature.id;
        map.setFeatureState({source:'kd-barangay-zone',id:hoveredId},{hover:true});
        map.getCanvas().style.cursor='pointer';
        tooltip.setLngLat(event.lngLat).setText('Barangay '+feature.properties.name).addTo(map);
      });
      map.on('mouseleave','kd-brgy-fill',()=>{
        if (hoveredId !== null && map.getSource('kd-barangay-zone'))
          map.setFeatureState({source:'kd-barangay-zone',id:hoveredId},{hover:false});
        hoveredId=null;map.getCanvas().style.cursor='';tooltip.remove();
      });
      map.on('click','kd-brgy-fill',event=>{
        const feature=event.features && event.features[0];
        if (feature) new maplibregl.Popup({offset:12}).setLngLat(event.lngLat)
          .setText('Barangay '+feature.properties.name+', Koronadal City').addTo(map);
      });
      if (refresh) refresh.addEventListener('click',()=>load(true));
      if (showAll) showAll.addEventListener('click',fitCity);
      window.addEventListener('resize',()=>map && map.resize(),{passive:true});
    } catch (_) {setStatus('Unable to initialize the OpenFreeMap zoning view. Refresh the page.');}
  }
  if (document.readyState==='loading') document.addEventListener('DOMContentLoaded',()=>init());
  else init();
})();
