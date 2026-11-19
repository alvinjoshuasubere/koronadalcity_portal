(function(){
  'use strict';
  const launcher=document.getElementById('kdChatLauncher');
  const panel=document.getElementById('kdChatPanel');
  const close=document.getElementById('kdChatClose');
  const log=document.getElementById('kdChatMessages');
  const input=document.getElementById('kdChatInput');
  const form=document.getElementById('kdChatForm');
  const clear=document.getElementById('kdChatClear');
  if (!launcher||!panel||!log||!input||!form) return;
  const conversation=[];
  const links=[
    ['Business permit application','https://koronadalcityportal.com/v2/login'],
    ['Verify business permit','https://bplo.koronadalcityonlineservices.com/'],
    ['Citizen registration','https://citizen.koronadalcityonlineservices.com/register'],
    ['Job opportunities','https://jobs.koronadalcityonlineservices.com/'],
    ['LGU recruitment','https://careers.koronadalcityonlineservices.com/'],
    ['MTOP verification','https://traffic.koronadalcityonlineservices.com/'],
    ['Online assistance','https://assistance.koronadalcityonlineservices.com/'],
    ['Emergency contacts','emergency-contacts']
  ];
  function append(text,who,url){
    const bubble=document.createElement('div');
    bubble.className='kd-chat-bubble '+(who==='you'?'kd-chat-user':'kd-chat-assistant');
    bubble.textContent=text;
    bubble.setAttribute('data-message-role',who==='you'?'question':'answer');
    if(url){
      const anchor=document.createElement('a');
      anchor.href=url;anchor.textContent=url.startsWith('#')?'View section':'Open service';
      if(!url.startsWith('#')){anchor.target='_blank';anchor.rel='noopener noreferrer';}
      bubble.appendChild(anchor);
    }
    log.appendChild(bubble);
    log.scrollTop=log.scrollHeight;
    return bubble;
  }
  const topics=[
    {name:'Business permits',words:/business|negosyo|bplo|renewal|licen[cs]e|permit|mayor.s permit|patente|tindahan|store|establish|trade/i,
     text:'For business applications and renewals, start with Business Permits & Licensing. For exact requirements and fees, confirm with BPLO.',url:links[0][1]},
    {name:'Citizen registration',words:/citizen|registr|profile|e.governance|epa|digital id|residen|profiling|account|sign.?up/i,
     text:'For city citizen registration and profiling, use Unified Citizen Services. The portal shows the current registration process.',url:links[2][1]},
    {name:'Job opportunities',words:/job|work|employment|vacanc|career|hiring|trabaho|pangabuhian|recruit|apply.*position/i,
     text:'For job listings, open the Koronadal Job Portal. For City Government positions, see the LGU Recruitment portal.',url:links[3][1]},
    {name:'Transport permits',words:/mtop|tricycle|operator|transport|traffic|prangkisa|franchise/i,
     text:'For available tricycle or transport operator permit information, open MTOP Verification.',url:links[5][1]},
    {name:'Online assistance',words:/assistance|financial|support|aid|ayuda|tabang|bulig|medical assistance|cash assistance|social welfare|cswdo|dswd/i,
     text:'For information about assistance programs, visit Online Assistance. The responsible office can confirm qualifications and documents.',url:links[6][1]},
    {name:'Barangay map',words:/barangay|map|boundar|zone|population|poblacion|census|residents/i,
     text:'Explore all 27 barangays on the interactive map. Zoom in or out, then hover or tap a barangay to see its displayed population.',url:'#live-city-data'},
    {name:'Emergency contacts',words:/emergency|ambulance|police|fire|911|sunog|aksidente|hotline|telephone|contact|phone number|tawag/i,
     text:'For an immediate emergency in the Philippines, call 911. For local agency numbers, use the City emergency contacts directory.',url:'emergency-contacts'}
  ];
  function normalizeQuestion(q){
    return String(q||'').toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g,'').trim();
  }
  function relatedTopics(q){
    const normalized=normalizeQuestion(q);
    return topics.filter(topic=>topic.words.test(normalized));
  }
  function appendChoices(bubble,items){
    if(!items||!items.length)return;
    const wrap=document.createElement('div');
    wrap.className='kd-chat-followups';
    items.slice(0,3).forEach(item=>{
      const button=document.createElement('button');
      button.type='button';button.className='kd-chat-followup';
      button.textContent=item.name;
      button.addEventListener('click',()=>{input.value=item.question||item.name;form.requestSubmit();});
      wrap.appendChild(button);
    });
    bubble.appendChild(wrap);
  }
  function fallbackAnswer(question,error){
    const matched=relatedTopics(question);
    const lower=normalizeQuestion(question);
    if(matched.length){
      const first=matched[0];
      return {text:'I can guide you to the right service, but I cannot confirm every detail of your question. '+first.text,
        url:first.url,choices:matched.slice(1).map(t=>({name:t.name}))};
    }
    if(/where|location|address|office|hours|schedule|requirement|document|fee|price|how much|pila|diin|paano|paagi|ano|what|how|why|when|saan/i.test(lower)){
      return {text:'I do not have verified details for that specific question. Is your concern about a permit, citizen registration, assistance, employment, or another city service? Choose a topic so I can point you to the correct page.',
        choices:[{name:'Business permits'},{name:'Citizen registration'},{name:'Online assistance'}]};
    }
    return {text:'I am not sure which city service you need. You can ask about permits, registration, assistance, jobs, barangays or emergency contacts. Choose a topic below and I will help you find the right page.',
      choices:[{name:'Business permits'},{name:'Online assistance'},{name:'Emergency contacts'}]};
  }
  function findHelp(question){
    const q=normalizeQuestion(question);
    if(/verify|verification|check.*permit|permit.*check|permit.*status|business record|business.*valid|validate/.test(q))
      return {text:'To check an available business permit record, open Verify Business Permit. I cannot access personal records or verify live application status here.',url:links[1][1]};
    if(/lgu recruit|city hall job|government job|government hiring|city government position/.test(q))
      return {text:'For City Government vacancies and recruitment announcements, open LGU Recruitment.',url:links[4][1]};
    if(/^(?:services|online services|available services|what services|ano.*services)/.test(q))
      return {text:'You can access business permits, permit verification, citizen registration, jobs, recruitment, MTOP verification and online assistance from the City portal.',url:'#services'};
    if(/^(hi|hello|hey|good morning|good afternoon|good evening|maayong|kumusta|what can you do|help me)[!?. ]*$/.test(q))
      return {text:'Maayong adlaw! Ask me about City of Koronadal services such as permits, registration, jobs, assistance, emergency contacts or the barangay map.'};
    const matches=relatedTopics(q);
    // Only route simple, recognizable service requests automatically. Complex or
    // multi-topic questions are handled by AI, or a helpful clarification fallback.
    if(matches.length===1 && q.split(/\\s+/).length<=12 && !/why|how long|require|how much|cost|fee|pila|qualified|eligible|when|schedule/.test(q)){
      return {text:matches[0].text,url:matches[0].url};
    }
    return null;
  }
  const greeting='Maayong adlaw! I am Ask Koronadal, your automated guide to online city services. Choose a topic below or type your question. Please do not share passwords or personal records.';
  if(clear)clear.addEventListener('click',()=>{
    if(busy)return;
    log.replaceChildren();
    conversation.length=0;
    append(greeting,'bot');
    input.focus();
  });
  function openPanel(){
    panel.hidden=false;launcher.setAttribute('aria-expanded','true');
    if (window.matchMedia('(min-width: 761px)').matches) input.focus();
  }
  function closePanel(){
    panel.hidden=true;launcher.setAttribute('aria-expanded','false');launcher.focus();
  }
  launcher.addEventListener('click',()=>panel.hidden?openPanel():closePanel());
  close.addEventListener('click',closePanel);
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!panel.hidden)closePanel();});
  document.querySelectorAll('[data-kd-chat-question]').forEach(button=>{
    button.addEventListener('click',()=>{
      input.value=button.dataset.kdChatQuestion||'';
      form.requestSubmit();
    });
  });
  let busy=false;
  form.addEventListener('submit',async event=>{
    event.preventDefault();
    if(busy)return;
    const question=input.value.trim();
    if(!question)return;
    input.value='';
    append(question,'you');
    const local=findHelp(question);
    if(local){append(local.text,'bot',local.url);if(window.matchMedia('(min-width: 761px)').matches)input.focus();return;}
    busy=true;
    const prior=conversation.slice(-6);
    const loading=append('Checking available information…','bot');
    loading.classList.add('kd-chat-thinking');
    const submit=form.querySelector('button[type="submit"]');
    if(submit)submit.disabled=true;
    try{
      const response=await fetch('portal-chat.php',{
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({message:question,history:prior})
      });
      const data=await response.json();
      if(!response.ok||!data.reply)throw new Error(data.error||'AI is unavailable');
      loading.classList.remove('kd-chat-thinking');
      loading.textContent=data.reply;
      if(data.suggestions && Array.isArray(data.suggestions))appendChoices(loading,data.suggestions);
      conversation.push({role:'user',content:question},{role:'assistant',content:data.reply});
      if(conversation.length>8)conversation.splice(0,conversation.length-8);
    }catch(_){
      loading.classList.remove('kd-chat-thinking');
      const guidance=fallbackAnswer(question);
      loading.textContent=guidance.text;
      if(guidance.url){
        const a=document.createElement('a');a.href=guidance.url;
        a.textContent=guidance.url.startsWith('#')?'View map or services':'Open recommended service';
        if(!guidance.url.startsWith('#')){a.target='_blank';a.rel='noopener noreferrer';}
        loading.appendChild(a);
      }
      appendChoices(loading,guidance.choices);
    }finally{busy=false;if(submit)submit.disabled=false;if(window.matchMedia('(min-width: 761px)').matches)input.focus();log.scrollTop=log.scrollHeight;}
  });
  append(greeting,'bot');
})();