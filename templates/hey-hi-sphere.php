<?php
// Template for onlymatt_hey_hi shortcode in the github clone.
$site = isset($site_info) ? $site_info : array();
?>

<style>
/* Minimal styles for helper menu */
.onlymatt-helper { position: fixed; right: 20px; bottom: 20px; z-index: 9999; font-family: Arial, sans-serif; }
.onlymatt-helper .om-btn { background:#0066cc;color:#fff;border:none;padding:10px 14px;border-radius:28px;cursor:pointer;box-shadow:0 4px 8px rgba(0,0,0,0.15);} 
.onlymatt-helper .om-panel { display:none; width:320px; background:#fff; border:1px solid #e6e6e6; box-shadow:0 8px 24px rgba(0,0,0,0.15); border-radius:8px; padding:12px; margin-bottom:8px; }
.onlymatt-helper .om-panel.open { display:block; }
.onlymatt-helper h4 { margin:0 0 8px 0; font-size:16px }
.onlymatt-helper .om-quick-links a{display:block;padding:6px 0;color:#0066cc;text-decoration:none}
.onlymatt-helper .om-actions{margin-top:8px}
.onlymatt-helper .om-actions button{margin-right:6px}
.onlymatt-helper .om-response{margin-top:8px;padding:8px;background:#f9f9f9;border-radius:6px;min-height:36px}
</style>

<div class="onlymatt-helper" aria-hidden="false">
    <div class="om-panel" role="dialog" aria-label="ONLYMATT helper menu">
        <h4>Assistant du site</h4>
        <div class="om-summary">Chargement des informations...</div>

        <div class="om-quick-links"><strong>Pages principales</strong><div class="om-pages"></div></div>

        <div class="om-actions">
            <input type="text" class="om-message" placeholder="Écrire un message rapide" style="width:60%" />
            <button class="om-send om-btn">Envoyer</button>
            <button class="om-hi om-btn">Dire bonjour</button>
        </div>

        <div class="om-response" aria-live="polite"></div>
    </div>

    <button class="om-toggle om-btn" aria-expanded="false" title="Ouvrir l'assistant">💬</button>
</div>

<script>
(function(){
    var container = document.querySelector('.onlymatt-helper');
    var toggle = container.querySelector('.om-toggle');
    var panel = container.querySelector('.om-panel');
    var pagesEl = container.querySelector('.om-pages');
    var summaryEl = container.querySelector('.om-summary');
    var respEl = container.querySelector('.om-response');
    var sendBtn = container.querySelector('.om-send');
    var hiBtn = container.querySelector('.om-hi');
    var messageInput = container.querySelector('.om-message');

    function openPanel() { panel.classList.add('open'); toggle.setAttribute('aria-expanded','true'); }
    function closePanel(){ panel.classList.remove('open'); toggle.setAttribute('aria-expanded','false'); }

    toggle.addEventListener('click', function(){
        if (panel.classList.contains('open')) closePanel(); else openPanel();
    });

    // Populate site info from localized onlymatt_ajax.site_info when available
    var site = (window.onlymatt_ajax && window.onlymatt_ajax.site_info) ? window.onlymatt_ajax.site_info : null;
    if (!site) {
        summaryEl.textContent = document.title || 'Bienvenue';
    } else {
        summaryEl.innerHTML = '<strong>' + (site.site_title || document.title) + '</strong><div style="font-size:12px;color:#666">' + (site.site_description || '') + '</div>';
        // pages
        if (site.main_pages && site.main_pages.length) {
            pagesEl.innerHTML = '';
            site.main_pages.forEach(function(p){
                var a = document.createElement('a'); a.href = p.url; a.textContent = p.title; pagesEl.appendChild(a);
            });
        }
    }

    function displayResponse(text){ respEl.textContent = text; }

    function callChat(message){
        displayResponse('...');
        var url = (window.location.origin || '') + '/wp-json/onlymatt/v1/chat';
        fetch(url, { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ message: message }) })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (!data) return displayResponse('Pas de réponse');
            if (data.response) return displayResponse(data.response);
            if (data.choices && data.choices[0] && data.choices[0].message) return displayResponse(data.choices[0].message.content || JSON.stringify(data));
            displayResponse(JSON.stringify(data));
        }).catch(function(err){ displayResponse('Erreur: ' + (err.message || err)); });
    }

    sendBtn.addEventListener('click', function(){ var m = messageInput.value || ''; if (!m) return; callChat(m); });
    hiBtn.addEventListener('click', function(){ callChat('Bonjour, peux-tu me présenter rapidement ce site ?'); });

    // close when clicking outside
    document.addEventListener('click', function(e){ if (!container.contains(e.target) && panel.classList.contains('open')) closePanel(); });
})();
</script>
