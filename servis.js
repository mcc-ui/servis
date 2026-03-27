(function () {
  // URL iframu — CDN odkaz na servis.html v GitHub repozitári
  var SRC = 'https://cdn.jsdelivr.net/gh/mcc-ui/servis/servis?embed=1';

  // Wrapper div — vložíme hneď za tento <script> tag
  var script = document.currentScript;
  var wrap   = document.createElement('div');
  wrap.style.cssText = 'width:100%;overflow:hidden;margin:0;padding:0';

  if (script && script.parentNode) {
    script.parentNode.insertBefore(wrap, script.nextSibling);
  } else {
    document.body.appendChild(wrap);
  }

  // Iframe
  var iframe = document.createElement('iframe');
  iframe.src = SRC;
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('scrolling', 'no');
  iframe.setAttribute('allowtransparency', 'true');
  iframe.style.cssText = [
    'width:100%',
    'min-height:520px',
    'border:none',
    'display:block',
    'overflow:hidden',
    'background:transparent',
    'transition:height 0.2s ease'
  ].join(';');
  wrap.appendChild(iframe);

  // Prijímaj správy o výške od iframu a prispôsob výšku
  window.addEventListener('message', function (e) {
    if (e.data && e.data.type === 'servisHeight') {
      iframe.style.height = Math.ceil(e.data.height) + 'px';
    }
  });
})();
