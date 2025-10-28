# AI Website Assistant - Plugin WordPress

Assistant IA intelligent intégré aux sites WordPress via Gateway OnlyMatt.

## 🚀 Fonctionnalités

- **Chat IA intégré** : Interface de chat moderne et responsive
- **Compatibilité Breakdance** : Fonctionne parfaitement avec le page builder Breakdance
- **Aperçu dans l'admin** : Visualisation directe des shortcodes dans WordPress Admin
- **Configuration API** : Connexion sécurisée au gateway OnlyMatt
- **Gestion d'erreurs** : Messages d'erreur informatifs et gestion robuste

## 📦 Installation

1. **Téléchargez** le plugin depuis GitHub
2. **Décompressez** l'archive `wp-ai-assistant/`
3. **Uploadez** le dossier `wp-ai-assistant/` dans `/wp-content/plugins/`
4. **Activez** le plugin dans WordPress Admin → Extensions

## ⚙️ Configuration

1. Allez dans **Extensions** → **AI Website Assistant**
2. **URL de l'API Gateway** : `https://api.onlymatt.ca` (ou votre URL personnalisée)
3. **Clé API** : Votre clé d'authentification OnlyMatt
4. **Connaissance du Site** : Description de votre site web
5. **Stratégies de Vente** : Instructions pour l'IA

## 🎯 Utilisation

### Shortcode principal
```php
[ai_assistant]
```

### Avec paramètres (optionnel)
```php
[ai_assistant position="bottom-left" size="large"]
```

### Dans un template PHP
```php
<?php echo do_shortcode('[ai_assistant]'); ?>
```

## 🔧 Paramètres disponibles

- `position` : `bottom-right` (défaut), `bottom-left`, `top-right`, `top-left`
- `size` : `medium` (défaut), `small`, `large`
- `mode` : `live` (défaut), `editor` (pour Breakdance)

## 🎨 Compatibilité Breakdance

Le plugin détecte automatiquement quand vous êtes dans l'éditeur Breakdance et affiche un aperçu approprié au lieu du chat actif.

## 🔗 API Gateway

Le plugin communique avec l'API OnlyMatt pour :
- Générer des réponses IA
- Stocker l'historique des conversations
- Gérer les erreurs de connexion

## 🛠️ Développement

### Structure du plugin
```
wp-ai-assistant/
├── ai-website-assistant.php    # Plugin principal
├── assets/
│   ├── css/
│   │   └── ai-assistant.css    # Styles
│   └── js/
│       └── ai-assistant.js     # JavaScript
└── index.php                   # Sécurité
```

### Hooks WordPress utilisés
- `wp_enqueue_scripts` : Chargement des assets
- `admin_enqueue_scripts` : Scripts d'administration
- `wp_ajax_*` : Gestion AJAX
- `add_shortcode` : Shortcode `[ai_assistant]`

## 📚 Historique des versions

### v1.0.0 (2025-10-28)
- ✅ Version initiale fonctionnelle
- ✅ Compatibilité Breakdance
- ✅ Aperçu dans l'admin
- ✅ Configuration API sécurisée

## 🆘 Support

Pour toute question ou problème :
1. Vérifiez la configuration API
2. Testez la connexion dans l'admin
3. Consultez les logs d'erreur WordPress

## 📄 Licence

GPL v2 or later

---

**Développé par OnlyMatt** 🤖</content>
<parameter name="filePath">/Users/mathieucourchesne/wp-ai-assistant/README.md