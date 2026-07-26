var reduce = matchMedia('(prefers-reduced-motion:reduce)').matches;

  /* theme toggle — persists the choice across pages (localStorage) */
  (function(){
    var r=document.documentElement,b=document.getElementById('themeBtn'),
        u=document.querySelector('#themeIco use'),t=document.getElementById('themeTxt');
    if(!b||!u||!t){return;}
    function sysDark(){return matchMedia('(prefers-color-scheme:dark)').matches}
    function isDark(){var v=r.getAttribute('data-theme');return v?v==='dark':sysDark()}
    function paint(){var d=isDark();u.setAttribute('href',d?'#i-moon':'#i-sun');t.textContent=d?'ليلي':'نهاري'}
    b.addEventListener('click',function(){
      var next=isDark()?'light':'dark';
      r.setAttribute('data-theme',next);
      try{localStorage.setItem('theme',next);}catch(e){}
      paint();
    });
    paint();
  })();

  /* reveal + count-up + ring draw */
  (function(){
    var els=document.querySelectorAll('.rise,.stagger');
    function fillCount(n){n.textContent=n.getAttribute('data-count')+(n.getAttribute('data-suffix')||'')}
    if(reduce||!('IntersectionObserver'in window)){
      els.forEach(function(e){e.classList.add('in')});
      var rg0=document.getElementById('ring');if(rg0)rg0.classList.add('on');
      document.querySelectorAll('[data-count]').forEach(fillCount);return;
    }
    var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){
      x.target.classList.add('in');io.unobserve(x.target);
      x.target.querySelectorAll('[data-count]').forEach(count);
    }})},{threshold:.16});
    els.forEach(function(e){if(!e.classList.contains('in'))io.observe(e)});
    var rg=document.getElementById('ring');if(rg)setTimeout(function(){rg.classList.add('on')},400);
    function count(n){var to=+n.getAttribute('data-count'),sf=n.getAttribute('data-suffix')||'',s=null;
      function step(ts){if(!s)s=ts;var p=Math.min((ts-s)/1200,1),e=1-Math.pow(1-p,3);
        n.textContent=Math.round(to*e)+sf;if(p<1)requestAnimationFrame(step)}requestAnimationFrame(step);}
  })();

  /* pointer parallax tilt on hero dashboard card */
  (function(){
    if(reduce)return;var card=document.getElementById('dash');if(!card)return;var raf=0;
    card.addEventListener('pointermove',function(e){
      if(raf)return;raf=requestAnimationFrame(function(){raf=0;
        var b=card.getBoundingClientRect(),x=(e.clientX-b.left)/b.width-.5,y=(e.clientY-b.top)/b.height-.5;
        card.style.transform='perspective(900px) rotateX('+(-y*5)+'deg) rotateY('+(x*6)+'deg)';});
    });
    card.addEventListener('pointerleave',function(){card.style.transform='';});
  })();

  /* pricing toggle */
  (function(){
    var m=document.getElementById('pmMonthly'),a=document.getElementById('pmAnnual'),
        p1n=document.getElementById('p1n'),p1p=document.getElementById('p1p'),
        p2n=document.getElementById('p2n'),p2p=document.getElementById('p2p');
    if(!m||!a||!p1n||!p2n){return;}
    function set(annual){
      m.classList.toggle('active',!annual);a.classList.toggle('active',annual);
      m.setAttribute('aria-pressed',String(!annual));a.setAttribute('aria-pressed',String(annual));
      if(annual){p1n.textContent='79';p1p.textContent='/ شهرياً (يُدفع سنوياً)';p2n.textContent='990';p2p.textContent='/ سنوياً';}
      else{p1n.textContent='99';p1p.textContent='/ شهرياً';p2n.textContent='990';p2p.textContent='/ سنوياً';}
    }
    m.addEventListener('click',function(){set(false)});
    a.addEventListener('click',function(){set(true)});
  })();

  /* mobile menu disclosure — the [hidden] attribute keeps it closed by default */
  (function(){
    var btn=document.getElementById('menuBtn'),menu=document.getElementById('mobileMenu');
    if(!btn||!menu)return;
    function setOpen(open){
      if(open){menu.removeAttribute('hidden');}else{menu.setAttribute('hidden','');}
      btn.setAttribute('aria-expanded',String(open));
    }
    setOpen(false);
    btn.addEventListener('click',function(){setOpen(menu.hasAttribute('hidden'));});
    menu.addEventListener('click',function(e){if(e.target.closest('a')){setOpen(false);}});
  })();
