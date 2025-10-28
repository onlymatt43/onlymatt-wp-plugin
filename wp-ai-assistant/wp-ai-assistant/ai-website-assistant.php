<?php
/**
 * Plugin Name: AI Website Assistant
 * Plugin URI: https://onlymatt-gateway.onrender.com
 * Description: Assistant IA intelligent intégré aux sites WordPress via Gateway. Guide les visiteurs, informe des nouveautés et optimise la conversion client.
 * Version: 1.0.0
 * Author: OnlyMatt
 * License: GPL v2 or later
 * Text Domain: ai-website-assistant
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Classe principale du plugin
class AI_Website_Assistant {

    private $api_base;
    private $api_key;
    private $site_knowledge;
    private $sales_strategies;

    public function __construct() {
        $this->api_base = get_option('aiwa_api_base', 'https://api.onlymatt.ca');
        $this->api_key = get_option('aiwa_api_key', '');
        $this->site_knowledge = get_option('aiwa_site_knowledge', '');
        $this->sales_strategies = get_option('aiwa_sales_strategies', '');

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_shortcode('ai_assistant', array($this, 'render_assistant_shortcode'));
        add_action('wp_ajax_aiwa_chat', array($this, 'handle_chat_request'));
        add_action('wp_ajax_nopriv_aiwa_chat', array($this, 'handle_chat_request'));
    }

    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            'AI Assistant',
            'AI Assistant',
            'manage_options',
            'ai-website-assistant',
            array($this, 'admin_page'),
            'dashicons-robot',
            30
        );
    }

    /**
     * Page d'administration
     */
    public function admin_page() {
        if (isset($_POST['aiwa_save_settings'])) {
            $this->save_settings();
            echo '<div class="notice notice-success"><p>Paramètres sauvegardés avec succès !</p></div>';
        }

        ?>
        <div class="wrap">
            <h1>AI Website Assistant</h1>

            <form method="post" action="">
                <?php wp_nonce_field('aiwa_settings_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">URL de l'API Gateway</th>
                        <td>
                            <input type="url" name="aiwa_api_base" value="<?php echo esc_attr($this->api_base); ?>" class="regular-text" required>
                            <p class="description">URL de base de votre gateway AI (ex: https://onlymatt-gateway.onrender.com)</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Clé API</th>
                        <td>
                            <input type="password" name="aiwa_api_key" value="<?php echo esc_attr($this->api_key); ?>" class="regular-text" required>
                            <p class="description">Votre clé d'authentification pour accéder au gateway</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Connaissance du Site</th>
                        <td>
                            <textarea name="aiwa_site_knowledge" rows="10" cols="50" class="large-text"><?php echo esc_textarea($this->site_knowledge); ?></textarea>
                            <p class="description">Décrivez votre site web, vos produits/services, votre mission, etc. L'AI utilisera ces informations pour guider les visiteurs.</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Stratégies de Vente</th>
                        <td>
                            <textarea name="aiwa_sales_strategies" rows="8" cols="50" class="large-text"><?php echo esc_textarea($this->sales_strategies); ?></textarea>
                            <p class="description">Définissez vos stratégies de vente et de rétention client. Comment convertir les visiteurs en clients ?</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Sauvegarder les paramètres', 'primary', 'aiwa_save_settings'); ?>
            </form>

            <hr>

            <h3>Exemple d'utilisation :</h3>
            <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;">
                <p><strong>Shortcode simple :</strong></p>
                <code style="background: #fff; padding: 5px; border: 1px solid #ddd; display: block; margin: 5px 0;">[ai_assistant]</code>

                <p><strong>Avec paramètres :</strong></p>
                <code style="background: #fff; padding: 5px; border: 1px solid #ddd; display: block; margin: 5px 0;">[ai_assistant position="bottom-left" size="large"]</code>

                <p><strong>Dans un template PHP :</strong></p>
                <code style="background: #fff; padding: 5px; border: 1px solid #ddd; display: block; margin: 5px 0;">&lt;?php echo do_shortcode('[ai_assistant]'); ?&gt;</code>
            </div>

            <h3>Aperçu du rendu :</h3>
            <div style="border: 1px solid #ddd; padding: 20px; background: #fafafa; border-radius: 5px;">
                <?php echo do_shortcode('[ai_assistant mode="editor"]'); ?>
            </div>

            <h2>Test de Connexion</h2>
            <button id="test-connection" class="button button-secondary">Tester la connexion au Gateway</button>
            <div id="connection-result"></div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#test-connection').click(function() {
                var apiBase = $('input[name="aiwa_api_base"]').val();
                var apiKey = $('input[name="aiwa_api_key"]').val();

                $('#connection-result').html('<p>Test en cours...</p>');

                $.ajax({
                    url: apiBase + '/health',
                    type: 'GET',
                    headers: {
                        'X-OM-Key': apiKey
                    },
                    success: function(response) {
                        $('#connection-result').html('<div class="notice notice-success"><p>✅ Connexion réussie ! Gateway opérationnel.</p></div>');
                    },
                    error: function(xhr, status, error) {
                        $('#connection-result').html('<div class="notice notice-error"><p>❌ Erreur de connexion : ' + error + '</p></div>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Sauvegarder les paramètres
     */
    private function save_settings() {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'aiwa_settings_nonce')) {
            return;
        }

        update_option('aiwa_api_base', sanitize_url($_POST['aiwa_api_base']));
        update_option('aiwa_api_key', sanitize_text_field($_POST['aiwa_api_key']));
        update_option('aiwa_site_knowledge', wp_kses_post($_POST['aiwa_site_knowledge']));
        update_option('aiwa_sales_strategies', wp_kses_post($_POST['aiwa_sales_strategies']));

        // Mettre à jour les propriétés de l'instance
        $this->api_base = get_option('aiwa_api_base');
        $this->api_key = get_option('aiwa_api_key');
        $this->site_knowledge = get_option('aiwa_site_knowledge');
        $this->sales_strategies = get_option('aiwa_sales_strategies');
    }

    /**
     * Scripts d'administration
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_ai-website-assistant') {
            return;
        }

        wp_enqueue_script('jquery');
    }

    /**
     * Scripts frontend
     */
    public function enqueue_frontend_scripts() {
        // Charger toujours les scripts pour éviter les problèmes de détection
        wp_enqueue_style('aiwa-styles', plugin_dir_url(__FILE__) . 'assets/css/ai-assistant.css', array(), '1.0.0');
        wp_enqueue_script('aiwa-script', plugin_dir_url(__FILE__) . 'assets/js/ai-assistant.js', array('jquery'), '1.0.0', true);

        wp_localize_script('aiwa-script', 'aiwa_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aiwa_chat_nonce')
        ));

        // Debug log
        error_log('AIWA: Scripts chargés');
    }

    /**
     * Shortcode pour afficher l'assistant
     */
    public function render_assistant_shortcode($atts) {
        // Debug log
        error_log('AIWA: Shortcode appelé avec attributs: ' . print_r($atts, true));

        $atts = shortcode_atts(array(
            'logo_url' => '',
            'position' => 'bottom-right',
            'size' => 'medium',
            'mode' => 'live' // 'live' ou 'editor' pour Breakdance
        ), $atts);

        // Détecter si on est dans Breakdance
        $is_breakdance = isset($_GET['breakdance']) || isset($_GET['action']) && $_GET['action'] === 'edit';
        if ($is_breakdance) {
            $atts['mode'] = 'editor';
        }

        error_log('AIWA: Mode détecté: ' . $atts['mode'] . ', Breakdance: ' . ($is_breakdance ? 'oui' : 'non'));

        ob_start();

        if ($atts['mode'] === 'editor') {
            // Mode éditeur Breakdance - affichage simplifié
            ?>
            <div class="ai-assistant-breakdance-editor" style="border: 2px dashed #667eea; padding: 20px; text-align: center; background: #f8f9ff; border-radius: 10px;">
                <div style="font-size: 48px; color: #667eea; margin-bottom: 10px;">🤖</div>
                <h3 style="color: #333; margin: 10px 0;">AI Website Assistant</h3>
                <p style="color: #666; margin: 5px 0;">Assistant IA intégré - Mode Éditeur</p>
                <p style="color: #999; font-size: 12px;">Le chat sera visible sur la page publiée</p>
                <div style="margin-top: 15px;">
                    <small style="color: #999;">Shortcode: [ai_assistant]</small>
                </div>
            </div>
            <?php
        } else {
            // Mode normal
            ?>
            <div class="ai-assistant-wrapper <?php echo $is_breakdance ? 'breakdance-mode' : ''; ?>" data-position="<?php echo esc_attr($atts['position']); ?>" data-size="<?php echo esc_attr($atts['size']); ?>">
                <div class="ai-assistant-logo" id="ai-assistant-trigger">
                    <?php if ($atts['logo_url'] && file_exists(str_replace(site_url(), ABSPATH, $atts['logo_url']))): ?>
                        <img src="<?php echo esc_url($atts['logo_url']); ?>" alt="AI Assistant">
                    <?php else: ?>
                        <div class="ai-assistant-logo-icon">🤖</div>
                    <?php endif; ?>
                </div>

                <div class="ai-assistant-window" id="ai-assistant-window" style="display: none;">
                    <div class="ai-assistant-header">
                        <h3>Assistant IA</h3>
                        <button class="ai-assistant-close" id="ai-assistant-close">&times;</button>
                    </div>

                    <div class="ai-assistant-messages" id="ai-assistant-messages">
                        <div class="ai-message ai-message-bot">
                            <div class="ai-message-content">
                                Bonjour ! Je suis votre assistant IA. Comment puis-je vous aider à découvrir notre site ?
                            </div>
                        </div>
                    </div>

                    <div class="ai-assistant-input">
                        <input type="text" id="ai-assistant-input" placeholder="Tapez votre message...">
                        <button id="ai-assistant-send">Envoyer</button>
                    </div>
                </div>
            </div>
            <?php
        }

        $output = ob_get_clean();
        error_log('AIWA: Shortcode output length: ' . strlen($output));
        return $output;
    }

    /**
     * Gérer les requêtes de chat
     */
    public function handle_chat_request() {
        check_ajax_referer('aiwa_chat_nonce', 'nonce');

        $message = sanitize_text_field($_POST['message']);
        $conversation_history = isset($_POST['history']) ? json_decode(stripslashes($_POST['history']), true) : array();

        if (empty($message)) {
            wp_send_json_error('Message vide');
            return;
        }

        // Construire le prompt système avec les connaissances du site
        $system_prompt = $this->build_system_prompt($message, $conversation_history);

        // Préparer les messages pour l'API
        $messages = array(
            array('role' => 'system', 'content' => $system_prompt)
        );

        // Ajouter l'historique récent
        foreach (array_slice($conversation_history, -5) as $msg) {
            $messages[] = array(
                'role' => $msg['role'],
                'content' => $msg['content']
            );
        }

        // Ajouter le message actuel
        $messages[] = array('role' => 'user', 'content' => $message);

        // Appeler l'API Gateway
        $response = $this->call_gateway_api($messages);

        if ($response) {
            wp_send_json_success(array('reply' => $response));
        } else {
            // Plus de détails sur l'erreur
            $error_details = array(
                'message' => 'Erreur lors de la communication avec l\'IA',
                'timestamp' => current_time('mysql'),
                'api_url' => $this->api_base,
                'has_api_key' => !empty($this->api_key)
            );

            error_log('AIWA Chat Error Details: ' . print_r($error_details, true));
            wp_send_json_error($error_details);
        }
    }

    /**
     * Construire le prompt système
     */
    private function build_system_prompt($user_message, $conversation_history) {
        $prompt = "Tu es un assistant IA spécialisé pour ce site web. ";

        if (!empty($this->site_knowledge)) {
            $prompt .= "\n\nCONNAISSANCES DU SITE :\n" . $this->site_knowledge;
        }

        if (!empty($this->sales_strategies)) {
            $prompt .= "\n\nSTRATÉGIES DE VENTE :\n" . $this->sales_strategies;
        }

        $prompt .= "\n\nINSTRUCTIONS :\n";
        $prompt .= "- Guide les visiteurs de manière naturelle et helpful\n";
        $prompt .= "- Utilise les connaissances du site pour répondre précisément\n";
        $prompt .= "- Applique les stratégies de vente de manière subtile\n";
        $prompt .= "- Si le visiteur montre de l'intérêt, propose des actions concrètes\n";
        $prompt .= "- Reste professionnel mais amical\n";
        $prompt .= "- Réponds en français\n";

        // Analyser l'intention du visiteur
        $intent = $this->analyze_user_intent($user_message, $conversation_history);
        if ($intent) {
            $prompt .= "\n\nINTENTION DÉTECTÉE : {$intent}";
        }

        return $prompt;
    }

    /**
     * Analyser l'intention de l'utilisateur
     */
    private function analyze_user_intent($message, $history) {
        $message = strtolower($message);

        // Mots-clés pour différentes intentions
        $intents = array(
            'information' => array('quoi', 'comment', 'où', 'quand', 'pourquoi', 'c\'est quoi'),
            'prix' => array('prix', 'coût', 'tarif', 'combien', '€', 'euros'),
            'contact' => array('contact', 'téléphone', 'email', 'appeler', 'joindre'),
            'achat' => array('acheter', 'commander', 'réservation', 'inscription'),
            'support' => array('aide', 'problème', 'bug', 'erreur', 'ne marche pas')
        );

        foreach ($intents as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $intent;
                }
            }
        }

        return 'general';
    }

    /**
     * Appeler l'API Gateway
     */
    private function call_gateway_api($messages) {
        $url = $this->api_base . '/ai/chat';

        // Extraire le dernier message utilisateur
        $user_message = '';
        $system_message = '';

        foreach ($messages as $msg) {
            if ($msg['role'] === 'user') {
                $user_message = $msg['content'];
            } elseif ($msg['role'] === 'system') {
                $system_message = $msg['content'];
            }
        }

        // Générer un thread_id unique pour cette conversation
        $thread_id = session_id() ?: uniqid('aiwa_', true);

        // Format simplifié qui fonctionne mieux
        $payload = array(
            'thread_id' => $thread_id,
            'message' => $user_message,
            'temperature' => 0.7
        );

        // Ajouter system_prompt seulement s'il existe
        if (!empty($system_message)) {
            $payload['system_prompt'] = $system_message;
        }

        error_log('AIWA API Payload: ' . print_r($payload, true));

        $args = array(
            'body' => wp_json_encode($payload),
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-OM-Key' => $this->api_key
            ),
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking' => true
        );

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            error_log('AIWA Gateway API Error: ' . $response->get_error_message());
            return false;
        }

        $http_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        error_log('AIWA API Response Code: ' . $http_code);
        error_log('AIWA API Response Body: ' . $body);

        // Si c'est une erreur serveur, retourner false
        if ($http_code >= 500) {
            error_log('AIWA Server Error (500+): ' . $body);
            return false;
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('AIWA JSON Decode Error: ' . json_last_error_msg() . ' | Raw response: ' . $body);
            return false;
        }

        return $data['response'] ?? $data['reply'] ?? $data['message'] ?? false;
    }
}

// Initialiser le plugin
new AI_Website_Assistant();

// Fonction d'activation
function aiwa_activate() {
    // Créer les options par défaut
    add_option('aiwa_api_base', 'https://api.onlymatt.ca');
    add_option('aiwa_api_key', '');
    add_option('aiwa_site_knowledge', '');
    add_option('aiwa_sales_strategies', '');
}
register_activation_hook(__FILE__, 'aiwa_activate');

// Fonction de désactivation
function aiwa_deactivate() {
    // Nettoyer si nécessaire
}
register_deactivation_hook(__FILE__, 'aiwa_deactivate');
