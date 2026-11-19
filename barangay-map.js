(function () {
  'use strict';
  const mapEl = document.getElementById('kdBarangayMap');
  const statusEl = document.getElementById('kdBarangayStatus');
  if (!mapEl || !statusEl) return;
  const counter = document.getElementById('kdZoneCount');
  const mapMessage = document.getElementById('kdMapMessage');
  const reload = document.getElementById('kdBarangayReload');
  const showAll = document.getElementById('kdBarangayShowAll');
  const filterList = document.getElementById('kdBarangayFilterList');
  const filterSearch = document.getElementById('kdBarangayFilterSearch');
  const filterCount = document.getElementById('kdFilterCount');
  const filterSearchResult = document.getElementById('kdFilterSearchResult');
  const selectAll = document.getElementById('kdSelectAllBarangays');
  const clearAll = document.getElementById('kdClearBarangays');
  const CITY_CENTER = [124.86566, 6.47965];
  const FALLBACK_BOUNDS = [[124.70,6.34],[125.04,6.60]];
  const officialNames = ['Assumption','Avanceña','Cacub','Caloocan','Carpenter Hill','Concepcion','Esperanza','General Paulino Santos','Mabini','Magsaysay','Mambucal','Morales','Namnama','New Pangasinan','Paraiso','Rotonda','San Isidro','San Jose','San Roque','Santa Cruz','Santo Niño','Saravia','Topland','Zone 1','Zone 2','Zone 3','Zone 4'];
  const normalize = name => String(name || '').toLowerCase().normalize('NFD')
    .replace(/[\u0300-\u036f]/g,'').replace(/^barangay\s+/,'').trim()
    .replace(/\bzone\s+(iv|iii|ii|i)\b/g, (_, roman)=>'zone '+({i:1,ii:2,iii:3,iv:4}[roman]))
    .replace(/\s+/g,' ');
  const lookup = new Map(officialNames.map(name=>[normalize(name),name]));
  // Match historical PSA names to the local names shown in the portal.
  [['sarabia','Saravia'],['zulueta','Topland'],['zone i','Zone 1'],
   ['zone ii','Zone 2'],['zone iii','Zone 3'],['zone iv','Zone 4'],
   ['gen. paulino santos','General Paulino Santos'],['general p. santos','General Paulino Santos'],
   ['sto. nino','Santo Niño'],['sta. cruz','Santa Cruz']]
    .forEach(([alias,name])=>lookup.set(normalize(alias),name));
  // Match by actual barangay names only; historical PSGC suffixes may not follow name order.
  lookup.set('sarabia','Saravia');
  const nameFromPSA = props => lookup.get(normalize(props.brgy_name || props.BRGY_NAME || props.name));
  const population2024 = new Map(officialNames.map((name,i)=>[name,[2458,4781,2818,5468,7962,6366,2015,22025,4147,3391,1434,9539,4705,5059,6796,4841,9088,9302,4537,20544,17064,9722,10441,4025,3419,12614,7283][i]]));
  const details = document.getElementById('kdBarangayDetails');
  let activeName = null;
  function showDetails(name) {
    activeName=name;
    if (!details) return;
    if (name) {
      const pop=population2024.get(name);
      details.textContent='Barangay '+name+' · Population '+(pop===undefined?'unavailable':pop.toLocaleString('en-PH'))+' (2024 PSA Census)';
    } else {
      details.textContent='City of Koronadal · 27 barangays · Total population 201,844 (2024 PSA Census)';
    }
  }
  const featureCollection = features => ({type:'FeatureCollection',features});
  const CACHE_KEY = 'koronadal-verified-local-27-v8';
  const PSA_BASE = 'https://portal.georisk.gov.ph/arcgis/rest/services/PSA/Barangay/MapServer/4/query';
  const PSA_QUERY = new URLSearchParams({
    where:"city_name LIKE '%Koronadal%' AND prov_name LIKE '%South Cotabato%'",
    outFields:'brgy_name,city_name,prov_name,city_code,brgy_code,psgc_10d,objectid',
    returnGeometry:'true',outSR:'4326',geometryPrecision:'5',f:'geojson'
  });
  const OVERPASS = 'https://overpass.kumi.systems/api/interpreter';
  let map = null, request = null, hoverId = null, pickedId = null, popup = null, cachedFeatures = [], currentSourceLabel = '';
  const selectedNames = new Set(officialNames);
  const setStatus = value => { statusEl.textContent = value; };
  function showMapMessage(message) { if(mapMessage){mapMessage.textContent=message || '';mapMessage.hidden=!message;} }
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
  function hideUnrelatedMapLabels() {
    if (!map || !map.getStyle()) return;
    const layers = map.getStyle().layers || [];
    layers.forEach(layer => {
      // OpenFreeMap's base labels must not compete with Koronadal barangay names.
      // Keep roads, terrain and water visible, but hide every base symbol label.
      if (layer.type === 'symbol' && !layer.id.startsWith('kd-') &&
          map.getLayoutProperty(layer.id, 'visibility') !== 'none') {
        try { map.setLayoutProperty(layer.id, 'visibility', 'none'); }
        catch (error) { console.warn('Could not hide map label:', layer.id, error); }
      }
    });
  }
  function cleanLocal(data) {
    if (!data || data.type!=='FeatureCollection' || !Array.isArray(data.features))
      throw new Error('Local boundary file is not GeoJSON');
    const seen=new Set(), features=[];
    for (const f of data.features) {
      const p=f.properties || {};
      const name=lookup.get(normalize(p.name || p.source_name));
      if (!isPolygon(f) || !name || seen.has(name) || !String(p.psgc || '').startsWith('1206306') || !isLocal(f.geometry)) continue;
      seen.add(name);
      features.push({type:'Feature',id:features.length+1,
        properties:{name,shade:features.length%4,population:population2024.get(name)},
        geometry:f.geometry});
    }
    if (features.length<1) throw new Error('No verified Koronadal polygons found in local data');
    return featureCollection(features);
  }
  function cleanPSA(data) {
    if (!data || !Array.isArray(data.features)) throw new Error('Boundary response has no features');
    const seen = new Set();
    const result = [];
    for (const feature of data.features) {
      if (!isPolygon(feature)) continue;
      const props = feature.properties || {};
      const name = nameFromPSA(props);
      const city = String(props.city_name || props.CITY_NAME || '');
      const province = String(props.prov_name || props.PROV_NAME || '');
      if (!name || seen.has(name) || !/koronadal/i.test(city) || !/south cotabato/i.test(province) || !isLocal(feature.geometry)) continue;
      seen.add(name);
      result.push({type:'Feature',id:result.length+1,properties:{name,shade:result.length%4,population:population2024.get(name)},geometry:feature.geometry});
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
      features.push({type:'Feature',id:features.length+1,properties:{name,shade:features.length%4,population:population2024.get(name)},geometry:feature.geometry});
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
  function selectedFeatures() {
    return cachedFeatures.filter(f=>selectedNames.has(f.properties.name)).map(f=>({
      ...f,properties:{...f.properties,picked:activeName===f.properties.name}
    }));
  }
  function updateFilters(fit) {
    if (filterList) filterList.querySelectorAll('input[type="checkbox"]').forEach(input=>{
      input.checked = selectedNames.has(input.value);
    });
    if (filterCount) filterCount.textContent = selectedNames.size === officialNames.length
      ? 'All 27 selected' : selectedNames.size + ' of 27 selected';
    if (selectAll) selectAll.disabled = selectedNames.size === officialNames.length;
    if (clearAll) clearAll.disabled = selectedNames.size === 0;
    const shown = selectedFeatures();
    if (map && map.getSource('kd-koronadal-barangays')) {
      if (hoverId!==null) {
        map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:false});
        hoverId=null;
      }
      if (popup) popup.remove();
      upsert('kd-koronadal-barangays',featureCollection(shown));
      if (fit) {
        const bounds = boundsFor(shown);
        if (!bounds.isEmpty()) map.fitBounds(bounds,{
          padding:innerWidth<600?34:65,maxZoom:13.8,duration:500
        });
        else frameCity();
      }
    }
    if (cachedFeatures.length) {
      if (map && map.getLayer('kd-zones-labels')) {
        map.setLayoutProperty('kd-zones-labels','visibility',activeName?'visible':'none');
      }
      const available = shown.length;
      if (counter) counter.textContent = available+' of '+cachedFeatures.length+' mapped barangays visible';
      setStatus(selectedNames.size+' barangays selected. '+available+
        ' mapped boundaries visible from '+currentSourceLabel+'. Hover or tap to see names.'+
        (available<selectedNames.size?' Some selected barangays have no available polygon.':''));
    }
  }
  function setupFilters() {
    if (filterList) filterList.addEventListener('change',event=>{
      const input=event.target;
      if (!input.matches('input[type="checkbox"]')) return;
      if (input.checked) selectedNames.add(input.value);
      else selectedNames.delete(input.value);
      const focused=selectedNames.size===1 ? Array.from(selectedNames)[0] : null;
      showDetails(focused);
      updateFilters(true);
    });
    if (selectAll) selectAll.addEventListener('click',()=>{
      officialNames.forEach(name=>selectedNames.add(name));
      showDetails(null);
      updateFilters(true);
    });
    if (clearAll) clearAll.addEventListener('click',()=>{
      selectedNames.clear();
      showDetails(null);
      updateFilters(false);
    });
    if (filterSearch && filterList) filterSearch.addEventListener('input',()=>{
      const term=normalize(filterSearch.value);
      let found=0;
      filterList.querySelectorAll('.kd-barangay-filter-item').forEach(item=>{
        const input=item.querySelector('input');
        const match=!term || normalize(input.value).includes(term);
        item.hidden=!match;
        if(match) found++;
      });
      if(filterSearchResult)filterSearchResult.textContent=term
        ? found+(found===1?' result':' results')
        : officialNames.length+' barangays';
    });
    showDetails(null);
    updateFilters(false);
  }
  function frameCity() {
    if (!map) return;
    // Programmatic fit keeps all Koronadal barangays in view on any screen size.
    const all = boundsFor(cachedFeatures);
    map.fitBounds(all.isEmpty() ? FALLBACK_BOUNDS : all,{
      padding:innerWidth<600?34:65,maxZoom:12.7,duration:550
    });
  }
  function upsert(id,data) {
    if (map.getSource(id)) map.getSource(id).setData(data);
    else map.addSource(id,{type:'geojson',data});
  }
  function render(collection,sourceLabel) {
    if (!map || !map.getStyle()) return;
    hideUnrelatedMapLabels();
    showMapMessage('');
    cachedFeatures = collection.features;
    currentSourceLabel=sourceLabel;
    upsert('kd-koronadal-barangays',featureCollection(selectedFeatures()));
    if (!map.getLayer('kd-zones-fill')) {
      map.addLayer({id:'kd-zones-fill',type:'fill',source:'kd-koronadal-barangays',
        paint:{'fill-color':['match',['get','shade'],0,'#b8dcff',1,'#cce7ff',2,'#9bccfa','#a9d3ff'],'fill-opacity':0.52}});
      map.addLayer({id:'kd-zones-hover',type:'fill',source:'kd-koronadal-barangays',
        paint:{'fill-color':'#2275dc','fill-opacity':['case',['boolean',['feature-state','hover'],false],0.38,0]}});
      map.addLayer({id:'kd-zones-outline',type:'line',source:'kd-koronadal-barangays',
        paint:{'line-color':['match',['get','shade'],0,'#1766b1',1,'#2474c9',2,'#155ea9','#347fd2'],
          'line-width':3.5,'line-opacity':1}});
      map.addLayer({id:'kd-zones-picked-fill',type:'fill',source:'kd-koronadal-barangays',
        paint:{'fill-color':'#267ce8',
          'fill-opacity':['case',['boolean',['get','picked'],false],0.45,0]}});
      map.addLayer({id:'kd-zones-picked-line',type:'line',source:'kd-koronadal-barangays',
        paint:{'line-color':'#104b99',
          'line-width':['case',['boolean',['get','picked'],false],5.5,0],
          'line-opacity':['case',['boolean',['get','picked'],false],1,0]}});
      map.addLayer({id:'kd-zones-labels',type:'symbol',source:'kd-koronadal-barangays',
        layout:{'visibility':'none','text-field':['get','name'],'text-size':12,
          'text-allow-overlap':true,'text-ignore-placement':true,
          'text-font':['Noto Sans Regular'],'text-max-width':11},
        paint:{'text-color':'#124f99','text-halo-color':'#ffffff','text-halo-width':2.2}});
    }
    updateFilters(false);
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
    if (!map || !map.getStyle()) return;
    if (request) request.abort();
    request=new AbortController();
    const current=request;
    if (!force) {
      const cached=fromCache();
      if (cached) render(cached.data,cached.source+' (cached)');
    }
    setStatus('Loading Koronadal City barangay boundaries...');
    if (!cachedFeatures.length) showMapMessage('Loading barangay map…');
    if (reload) reload.disabled=true;
    const candidates = [
      async ()=>({data:cleanLocal(await getJson('data/koronadal-barangays.geojson?v=20261119-1',{},10000)),source:'Bundled PSGC map'}),
      async ()=>({data:cleanPSA(await getJson('barangay-boundaries.php?v=4',{},16000)),source:'PSA'}),
      async ()=>({data:cleanPSA(await getJson(PSA_BASE+'?'+PSA_QUERY,{},18000)),source:'PSA'}),
      async ()=>{
        const query='[out:json][timeout:18];relation["boundary"="administrative"]["admin_level"~"^(6|8)$"]["name"~"Koronadal",i](6.34,124.70,6.60,125.04)->.city;.city map_to_area->.a;relation["boundary"="administrative"]["admin_level"="10"](area.a);out body;>;out skel qt;';
        const json=await getJson(OVERPASS,{method:'POST',body:new URLSearchParams({data:query})},21000);
        return {data:cleanOSM(json),source:'OpenStreetMap'};
      }
    ];
    const merged = new Map();
    const sourceNames = new Set();
    let lastSuccess = false;
    for (const run of candidates) {
      if (current.signal.aborted) break;
      try {
        const result = await run();
        if (!result.data.features.length) throw Error('No matching Koronadal barangays returned');
        sourceNames.add(result.source);
        for (const feature of result.data.features) {
          if (!merged.has(feature.properties.name)) merged.set(feature.properties.name,feature);
        }
        const sorted = officialNames.filter(name=>merged.has(name)).map((name,i)=>({
          ...merged.get(name),id:i+1,properties:{...merged.get(name).properties,name,shade:i%4}
        }));
        const combined = featureCollection(sorted);
        // Show boundaries as soon as the first source responds.
        render(combined,Array.from(sourceNames).join(' + '));
        saveCache(combined,Array.from(sourceNames).join(' + '));
        lastSuccess = true;
        if (merged.size===27) break;
      } catch(error) {
        if (!current.signal.aborted) console.warn('Koronadal map source:',error);
      }
    }
    if (current.signal.aborted) return;
    if (!lastSuccess) {
      const cached=fromCache();
      if (cached) render(cached.data,cached.source+' (saved copy)');
      else {
        if (counter) counter.textContent='Boundaries unavailable';
        setStatus('Unable to load Koronadal barangay map. Please refresh.');
        showMapMessage('Map boundaries are unavailable. Select Refresh boundaries to try again.');
      }
    }
    if (reload) reload.disabled=false;
  }
  function initialize(tries=0) {
    if (!window.maplibregl) {
      if (tries<40) {setTimeout(()=>initialize(tries+1),200);return;}
      setStatus('Map scripts could not load. Refresh the page to try again.');showMapMessage('Map is unavailable. Refresh this page to try again.');return;
    }
    try {
      map=new maplibregl.Map({container:mapEl,style:'https://tiles.openfreemap.org/styles/positron',
        center:CITY_CENTER,zoom:10.7,
        dragPan:true,scrollZoom:true,boxZoom:false,doubleClickZoom:true,
        touchZoomRotate:true,keyboard:true,dragRotate:false,pitchWithRotate:false,
        attributionControl:false,maxBounds:[[124.56,6.18],[125.18,6.78]]});
      map.addControl(new maplibregl.AttributionControl({compact:true}),'bottom-right');
      map.touchZoomRotate.enable();
      map.touchZoomRotate.disableRotation();
      map.scrollZoom.enable();
      map.doubleClickZoom.enable();
      map.boxZoom.disable();
      map.dragRotate.disable();
      map.keyboard.enable();
      map.addControl(new maplibregl.NavigationControl({showCompass:false,visualizePitch:false}),'top-right');
      popup=new maplibregl.Popup({closeButton:false,closeOnClick:false,offset:12,className:'kd-barangay-tooltip'});
      setupFilters();
      map.once('load',()=>{
        const loading=mapEl.querySelector('.kd-barangay-map-loading');
        if(loading)loading.remove();
        map.resize();
        load(false);
      });
      map.on('error',event=>{
        console.warn('Koronadal map:',event.error || event);
        if(!cachedFeatures.length)showMapMessage('Map tiles could not load. Check your connection or refresh this page.');
      });
      map.on('styledata',hideUnrelatedMapLabels);
      map.on('mousemove','kd-zones-fill',event=>{
        if (!event.features || !event.features.length) return;
        const feature=event.features[0];
        if (hoverId!==null && map.getSource('kd-koronadal-barangays'))
          map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:false});
        hoverId=feature.id;
        map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:true});
        popup.setLngLat(event.lngLat).setText('Barangay '+feature.properties.name+' · Population '+(population2024.has(feature.properties.name) ? population2024.get(feature.properties.name).toLocaleString('en-PH') : 'unavailable')+' (2024 PSA Census)').addTo(map);
        map.getCanvas().style.cursor='pointer';
      });
      map.on('mouseleave','kd-zones-fill',()=>{
        if (hoverId!==null && map.getSource('kd-koronadal-barangays'))
          map.setFeatureState({source:'kd-koronadal-barangays',id:hoverId},{hover:false});
        hoverId=null;popup.remove();map.getCanvas().style.cursor='';
      });
      map.on('click','kd-zones-fill',event=>{
        const feature=event.features && event.features[0];
        if (!feature) return;
        if(popup)popup.remove();
        popup=new maplibregl.Popup({offset:12,closeButton:true}).setLngLat(event.lngLat)
          .setText('Barangay '+feature.properties.name+' · Population '+(population2024.has(feature.properties.name) ? population2024.get(feature.properties.name).toLocaleString('en-PH') : 'unavailable')+' (2024 PSA Census)').addTo(map);
      });
      if (reload) reload.addEventListener('click',()=>load(true));
      if (showAll) showAll.addEventListener('click',()=>{
        officialNames.forEach(name=>selectedNames.add(name));
        if (filterSearch) filterSearch.value='';
      if (filterSearchResult) filterSearchResult.textContent=officialNames.length+' barangays';
        if (filterList) filterList.querySelectorAll('.kd-barangay-filter-item').forEach(item=>item.hidden=false);
        showDetails(null);
        updateFilters(false);
        frameCity();
      });
      window.addEventListener('resize',()=>{map.resize();},{passive:true});
      document.querySelectorAll('a[href="#live-city-data"]').forEach(a=>a.addEventListener('click',()=>setTimeout(()=>map.resize(),400)));
      if (typeof ResizeObserver!=='undefined') new ResizeObserver(()=>map.resize()).observe(mapEl);
    } catch(error) {
      setStatus('Map initialization failed: '+error.message);
      showMapMessage('Map could not start. Refresh this page to try again.');
    }
  }
  if (document.readyState==='loading') document.addEventListener('DOMContentLoaded',()=>initialize());
  else initialize();
})();
