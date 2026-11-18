(function () {
  'use strict';
  const mapEl = document.getElementById('kdBarangayMap');
  const statusEl = document.getElementById('kdBarangayStatus');
  if (!mapEl || !statusEl) return;
  const counter = document.getElementById('kdZoneCount');
  const reload = document.getElementById('kdBarangayReload');
  const showAll = document.getElementById('kdBarangayShowAll');
  const CITY_CENTER = [124.86566, 6.47965];
  const FALLBACK_BOUNDS = [[124.70,6.34],[125.04,6.60]];
  const officialNames = ['Assumption','Avanceña','Cacub','Caloocan','Carpenter Hill','Concepcion','Esperanza','General Paulino Santos','Mabini','Magsaysay','Mambucal','Morales','Namnama','New Pangasinan','Paraiso','Rotonda','San Isidro','San Jose','San Roque','Santa Cruz','Santo Niño','Saravia','Topland','Zone 1','Zone 2','Zone 3','Zone 4'];
  const normalize = name => String(name || '').toLowerCase().normalize('NFD')
    .replace(/[\u0300-\u036f]/g,'').replace(/^barangay\s+/,'').trim()
    .replace(/\bzone\s+(iv|iii|ii|i)\b/g, (_, roman)=>'zone '+({i:1,ii:2,iii:3,iv:4}[roman]))
    .replace(/\s+/g,' ');
  const lookup = new Map(officialNames.map(name=>[normalize(name),name]));
  const featureCollection = features => ({type:'FeatureCollection',features});
  const CACHE_KEY = 'koronadal-psa-27-barangays-v1';
  const PSA_BASE = 'https://portal.georisk.gov.ph/arcgis/rest/services/PSA/Barangay/MapServer/4/query';
  const PSA_QUERY = new URLSearchParams({
    where:"city_name LIKE '%Koronadal%' AND prov_name LIKE '%South Cotabato%'",
    outFields:'brgy_name,city_name,city_code,brgy_code,objectid',
    returnGeometry:'true',outSR:'4326',geometryPrecision:'5',f:'geojson'
  });
  const OVERPASS = 'https://overpass.kumi.systems/api/interpreter';
  let map = null, request = null, hoverId = null, popup = null, cachedFeatures = [], cityShape = null;
  const setStatus = value => { statusEl.textContent = value; };
  function nestedCoordinates(geometry, fn) {
    const walk = item => {
      if (!Array.isArray(item)) return;
      if (typeof item[0] === 'number') {fn(item);return;}
      item.forEach(walk);
    };
    walk(geometry && geometry.coordinates);
  }
  function isLocal(geometry) {
    let local = false;
    nestedCoordinates(geometry,point=>{
      if (point[0]>=124.70 && point[0]<=125.04 && point[1]>=6.34 && point[1]<=6.60) local=true;
    });
    return local;
  }
  function isPolygon(f) {
    return f && f.geometry && ['Polygon','MultiPolygon'].includes(f.geometry.type);
  }
  function cleanPSA(data) {
    if (!data || !Array.isArray(data.features)) throw new Error('Boundary response has no features');
    const seen = new Set();
    const result = [];
    for (const feature of data.features) {
      if (!isPolygon(feature)) continue;
      const props = feature.properties || {};
      const rawName = props.brgy_name || props.BRGY_NAME || props.name;
      const name = lookup.get(normalize(rawName));
      const city = String(props.city_name || props.CITY_NAME || '');
      const province = String(props.prov_name || props.PROV_NAME || 'South Cotabato');
      if (!name || seen.has(name) || !/koronadal/i.test(city) || !/south cotabato/i.test(province) || !isLocal(feature.geometry)) continue;
      seen.add(name);
      result.push({type:'Feature',id:result.length+1,properties:{name,shade:result.length%4},geometry:feature.geometry});
    }
    return featureCollection(result);
  }
  function cleanOSM(data) {
    if (typeof window.osmtogeojson !== 'function') throw new Error('OSM conversion unavailable');
    const geo = window.osmtogeojson(data);
    const seen = new Set();
    const features = [];
    for (const feature of geo.features || []) {
      if (!isPolygon(feature) || !isLocal(feature.geometry)) continue;
      const p = feature.properties || {};
      const t = {...(p.tags || {}),...p};
      const name = lookup.get(normalize(t.name));
      if (!name || seen.has(name) || String(t.admin_level)!=='10') continue;
      seen.add(name);
      features.push({type:'Feature',id:features.length+1,properties:{name,shade:features.length%4},geometry:feature.geometry});
    }
    return featureCollection(features);
  }
  async function getJson(url,options={},maxMs=18000) {
    const controller = new AbortController();
    const stop=()=>controller.abort();
    if (request) request.signal.addEventListener('abort',stop,{once:true});
    const timer = setTimeout(stop,maxMs);
    try {
      const response = await fetch(url,{...options,signal:controller.signal,cache:'no-store'});
      if (!response.ok) throw new Error('HTTP '+response.status);
      const json = await response.json();
      if (json.error) throw new Error(json.error.message || 'Boundary service returned an error');
      return json;
    } finally {
      clearTimeout(timer);
      if (request) request.signal.removeEventListener('abort',stop);
    }
  }
  function boundsFor(features) {
    const b = new maplibregl.LngLatBounds();
    for (const feature of features) nestedCoordinates(feature.geometry,point=>{
      if (point[0]>=124.67 && point[0]<=125.08 && point[1]>=6.30 && point[1]<=6.65) b.extend(point);
    });
    return b;
  }
  function frameCity() {
    if (!map) return;
    const b = boundsFor(cachedFeatures);
    map.fitBounds(b.isEmpty() ? FALLBACK_BOUNDS : b,{
      padding:innerWidth<600?22:55,maxZoom:12.6,duration:500
    });
  }
  function upsert(id,data) {
    if (map.getSource(id)) map.getSource(id).setData(data);
    else map.addSource(id,{type:'geojson',data});
  }
  function render(collection,sourceLabel) {
    if (!map || !map.isStyleLoaded()) return;
    cachedFeatures = collection.features;
    upsert('kd-koronadal-barangays',collection);
    if (!map.getLayer('kd-zones-fill')) {
      map.addLayer({id:'kd-zones-fill',type:'fill',source:'kd-koronadal-barangays',
        paint:{'fill-color':['match',['get','shade'],0,'#68c7eb',1,'#91bff6',2,'#b3d7ed','#78c2cf'],'fill-opacity':0.37}});
      map.addLayer({id:'kd-zones-hover',type:'fill',source:'kd-koronadal-barangays',
        paint:{'fill-color':'#155fa6','fill-opacity':['case',['boolean',['feature-state','hover'],false],0.38,0]}});
      map.addLayer({id:'kd-zones-outline',type:'line',source:'kd-koronadal-barangays',
        paint:{'line-color':'#12599c','line-width':['interpolate',['linear'],['zoom'],10,1.6,14,3.2]}});
      map.addLayer({id:'kd-zones-labels',type:'symbol',source:'kd-koronadal-barangays',
        layout:{'text-field':['get','name'],'text-size':['interpolate',['linear'],['zoom'],10,10,14,13],
          'text-font':['Noto Sans Regular'],'text-max-width':11},
        paint:{'text-color':'#123b69','text-halo-color':'#ffffff','text-halo-width':1.8}});
    }
    if (counter) counter.textContent = collection.features.length+' / 27 barangays mapped';
    setStatus(collection.features.length+' of 27 Koronadal barangay boundaries loaded from '+sourceLabel+
      '. Hover or tap a barangay to see its name.'+
      (collection.features.length<27?' Some boundaries are unavailable in this dataset.':''));
    frameCity();
  }
  function saveCache(collection,source) {
    try {localStorage.setItem(CACHE_KEY,JSON.stringify({at:Date.now(),source,data:collection}));} catch(_) {}
  }
  function fromCache() {
    try {
      const value=JSON.parse(localStorage.getItem(CACHE_KEY));
      if (value && value.data && Array.isArray(value.data.features) && value.data.features.length &&
          Date.now()-value.at<7*24*60*60*1000) return value;
    } catch(_) {}
    return null;
  }
  async function load(force) {
    if (!map || !map.isStyleLoaded()) return;
    if (request) request.abort();
    request=new AbortController();
    const current=request;
    if (!force) {
      const cached=fromCache();
      if (cached) render(cached.data,cached.source+' (cached)');
    }
    setStatus('Loading Koronadal City barangay boundaries...');
    if (reload) reload.disabled=true;
    const candidates = [
      async ()=>({data:cleanPSA(await getJson('barangay-boundaries.php?v=2',{},16000)),source:'PSA'}),
      async ()=>({data:cleanPSA(await getJson(PSA_BASE+'?'+PSA_QUERY,{},18000)),source:'PSA'}),
      async ()=>{
        const query='[out:json][timeout:18];relation["boundary"="administrative"]["admin_level"="6"]["name"~"Koronadal",i](6.34,124.70,6.60,125.04)->.city;.city map_to_area->.a;relation["boundary"="administrative"]["admin_level"="10"](area.a);out body;>;out skel qt;';
        const json=await getJson(OVERPASS,{method:'POST',body:new URLSearchParams({data:query})},21000);
        return {data:cleanOSM(json),source:'OpenStreetMap'};
      }
    ];
    let best=null;
    for (const run of candidates) {
      if (current.signal.aborted) break;
      try {
        const result=await run();
        if (!result.data.features.length) throw Error('No matching Koronadal barangays returned');
        if (!best || result.data.features.length>best.data.features.length) best=result;
        if (result.data.features.length===27) break;
      } catch(error) {
        if (!current.signal.aborted) console.warn('Koronadal barangay boundary source:',error.message);
      }
    }
    if (current.signal.aborted) return;
    if (best) {
      render(best.data,best.source);
      saveCache(best.data,best.source);
    } else {
      const cached=fromCache();
      if (cached) {render(cached.data,cached.source+' (saved copy)');}
      else {
        if (counter) counter.textContent='Boundaries unavailable';
        setStatus('Boundary services are temporarily unavailable. The map remains available. Select Refresh boundaries to retry.');
      }
    }
    if (reload) reload.disabled=false;
  }
  function initialize(tries=0) {
    if (!window.maplibregl || !window.osmtogeojson) {
      if (tries<40) {setTimeout(()=>initialize(tries+1),200);return;}
      setStatus('Map scripts could not load. Refresh the page to try again.');return;
    }
    try {
      map=new maplibregl.Map({container:mapEl,style:'https://tiles.openfreemap.org/styles/positron',
        center:CITY_CENTER,zoom:11.2,attributionControl:false,maxBounds:[[124.56,6.18],[125.18,6.78]]});
      map.addControl(new maplibregl.NavigationControl({showCompass:false}),'top-right');
      map.addControl(new maplibregl.AttributionControl({compact:true}),'bottom-right');
      popup=new maplibregl.Popup({closeButton:false,closeOnClick:false,offset:12,className:'kd-barangay-tooltip'});
      map.once('load',()=>load(false));
      map.on('mousemove','kd-zones-fill',event=>{
        if (!event.features || !event.features.length) return;
        const feature=event.features[0];
        if (hoverId!==null && map.getSource('kd-koronadal-barangays'))
          map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:false});
        hoverId=feature.id;
        map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:true});
        popup.setLngLat(event.lngLat).setText('Barangay '+feature.properties.name).addTo(map);
        map.getCanvas().style.cursor='pointer';
      });
      map.on('mouseleave','kd-zones-fill',()=>{
        if (hoverId!==null && map.getSource('kd-koronadal-barangays'))
          map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:false});
        hoverId=null;popup.remove();map.getCanvas().style.cursor='';
      });
      map.on('click','kd-zones-fill',event=>{
        const feature=event.features && event.features[0];
        if (feature) new maplibregl.Popup({offset:12}).setLngLat(event.lngLat)
          .setText('Barangay '+feature.properties.name+', Koronadal City').addTo(map);
      });
      if (reload) reload.addEventListener('click',()=>load(true));
      if (showAll) showAll.addEventListener('click',frameCity);
      window.addEventListener('resize',()=>map.resize(),{passive:true});
    } catch(error) {
      setStatus('Map initialization failed: '+error.message);
    }
  }
  if (document.readyState==='loading') document.addEventListener('DOMContentLoaded',()=>initialize());
  else initialize();
})();
