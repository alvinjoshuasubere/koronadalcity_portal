(function(){
  'use strict';
  const launcher=document.getElementById('kdChatLauncher');
  const panel=document.getElementById('kdChatPanel');
  const close=document.getElementById('kdChatClose');
  const log=document.getElementById('kdChatMessages');
  const input=document.getElementById('kdChatInput');
  const form=document.getElementById('kdChatForm');
  if (!launcher||!panel||!log||!input||!form) return;
  const links=[
    ['Business permit application','https://koronadalcityportal.com/v2/login'],
    ['Verify business permit','https://bplo.koronadalcityonlineservices.com/'],
    ['Citizen registration','https://citizen.koronadalcityonlineservices.com/register'],
    ['Job opportunities','https://jobs.koronadalcityonlineservices.com/'],
    ['LGU recruitment','https://careers.koronadalcityonlineservices.com/'],
    ['MTOP verification','https://traffic.koronadalcityonlineservices.com/'],
    ['Online assistance','https://assistance.koronadalcityonlineservices.com/'],
    ['Emergency contacts','/emergency-contacts']
  ];
  function append(text,who,url){
    const bubble=document.createElement('div');
    bubble.className='kd-chat-bubble '+(who==='you'?'kd-chat-user':'kd-chat-assistant');
    bubble.textContent=text;
    if(url){
      const anchor=document.createElement('a');
      anchor.href=url;anchor.textContent='Open service';
      anchor.target='_blank';anchor.rel='noopener noreferrer';
      bubble.appendChild(anchor);
    }
    log.appendChild(bubble);
    log.scrollTop=log.scrollHeight;
    return bubble;
  }
  function findHelp(question){
    const q=question.toLowerCase();
    if(/emergency|ambulance|police|fire|911|sunog|aksidente/.test(q)){
      return {text:'For immediate emergencies in the Philippines, call 911. You can also open the city emergency contacts directory.',url:'/emergency-contacts'};
    }
    if(/verify|verification|check.*permit|permit.*check|permit.*status|business record|business.*valid/.test(q)){
      return {text:'You can view available business permit records through the BPLO Verify Business Permit portal. I cannot access your private permit information.',url:links[1][1]};
    }
    if(/business|bplo|renewal|licen[cs]e|permit/.test(q)){
      return {text:'For new business permit applications and renewals, open Business Permits & Licensing.',url:links[0][1]};
    }
    if(/citizen|registration|profile|e.governance|epa|digital id/.test(q)){
      return {text:'Use Unified Citizen Services for citizen registration and digital citizen profiles.',url:links[2][1]};
    }
    if(/recruit|government job|lgu job|city hall job/.test(q)){
      return {text:'For City Government recruitment announcements and application information, visit LGU Recruitment.',url:links[4][1]};
    }
    if(/job|work|employment|vacanc|career/.test(q)){
      return {text:'You can browse current listings on the Koronadal Job Portal.',url:links[3][1]};
    }
    if(/mtop|tricycle|operator|transport|traffic/.test(q)){
      return {text:'Use MTOP Verification to check available transport operator permit information.',url:links[5][1]};
    }
    if(/assistance|help|social|financial|support|aid/.test(q)){
      return {text:'You can access citizen support services and assistance information through Online Assistance.',url:links[6][1]};
    }
    if(/barangay|map|boundar|zone/.test(q)){
      return {text:'Scroll to Explore the Barangays to see the Koronadal map. Select one, several, or all barangays to show available boundary outlines.',url:'#live-city-data'};
    }
    return null;
  }
  function openPanel(){
    panel.hidden=false;launcher.setAttribute('aria-expanded','true');
    input.focus();
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
    if(local){append(local.text,'bot',local.url);return;}
    busy=true;
    const loading=append('Checking available information…','bot');
    const submit=form.querySelector('button[type="submit"]');
    if(submit)submit.disabled=true;
    try{
      const response=await fetch('portal-chat.php',{
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({message:question})
      });
      const data=await response.json();
      if(!response.ok||!data.reply)throw new Error(data.error||'AI is unavailable');
      loading.textContent=data.reply;
    }catch(_){
      loading.textContent='I can help you find Koronadal online services, but I cannot answer that question right now. Select a topic below or visit the City Government website.';
      const a=document.createElement('a');a.href='https://koronadal.gov.ph/';a.target='_blank';a.rel='noopener noreferrer';a.textContent='City Government website';loading.appendChild(a);
    }finally{busy=false;if(submit)submit.disabled=false;log.scrollTop=log.scrollHeight;}
  });
  append('Maayong adlaw! I am the automated Koronadal Portal Assistant. Ask me about city services, business permits, jobs or barangay maps. Please do not share passwords or personal records.','bot');
})();