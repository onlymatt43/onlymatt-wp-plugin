<?php
// Template for onlymatt_hey_hi shortcode in the github clone.
$site = isset($site_info) ? $site_info : array();
?>
<div class="onlymatt-hey-hi-widget">
    <h3><?php echo esc_html( $site['site_title'] ?? get_bloginfo('name') ); ?></h3>
    <p><?php echo esc_html( $site['site_description'] ?? get_bloginfo('description') ); ?></p>
    <div class="onlymatt-hey-hi-actions">
        <button class="onlymatt-hey-hi-open">Dire bonjour</button>
    </div>
</div>
<div class="onlymatt-hey-hi-response" style="margin-top:8px;"></div>

<script type="text/javascript">
(function(){
    var btn = document.querySelector('.onlymatt-hey-hi-open');
    var respEl = document.querySelector('.onlymatt-hey-hi-response');
    if (!btn) return;
    btn.addEventListener('click', function(){
        btn.disabled = true;
        respEl.textContent = '...';

        // Use WP REST API route registered in the plugin
        var url = (window.location.origin || '') + '/wp-json/onlymatt/v1/chat';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: 'Bonjour' })
        }).then(function(r){
            return r.json();
        }).then(function(data){
            if (!data) {
                respEl.textContent = 'Pas de réponse.';
            } else if (data.response) {
                respEl.textContent = data.response;
            } else if (data.choices && data.choices[0] && data.choices[0].message) {
                respEl.textContent = data.choices[0].message.content || JSON.stringify(data);
            } else {
                respEl.textContent = JSON.stringify(data);
            }
        }).catch(function(err){
            respEl.textContent = 'Erreur : ' + (err && err.message ? err.message : String(err));
        }).finally(function(){
            btn.disabled = false;
        });
    });
})();
</script>
