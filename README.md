# ONLYMATT AI WordPress Plugin

A comprehensive WordPress plugin that integrates the ONLYMATT AI assistant system into your WordPress website.

> ℹ️ Cette copie vit directement dans le repo `onlymatt-gateway`. Utilisez `./sync-to-wordpress.sh` pour la déployer vers votre installation WordPress locale ou distante afin de garantir qu'une seule version du plugin est utilisée partout.

## Features

- 🤖 **AI Chat Widget**: Interactive chat widget for your website visitors
- 🧠 **Memory Management**: Persistent AI memory across sessions
- 📋 **Task Management**: Create and track AI-assisted tasks
- 📊 **Reports & Analytics**: Generate usage and performance reports
- 🔧 **Admin Dashboard**: Complete admin interface for managing the AI system
- 🎨 **Responsive Design**: Mobile-friendly interface with Bootstrap styling

## Installation

1. **Download the plugin files** to your WordPress plugins directory:
   ```
   wp-content/plugins/onlymatt-wp-plugin/
   ```

2. **Activate the plugin** through the WordPress admin dashboard:
   - Go to Plugins → Installed Plugins
   - Find "ONLYMATT AI Assistant"
   - Click "Activate"

3. **Configure the plugin**:
   - Go to ONLYMATT AI → Settings in the admin menu
   - Enter your API Gateway URL (from your Render deployment)
   - Enter your Admin API Key
   - Save the settings

## Configuration

### Required Settings

- **API Gateway URL**: The URL of your ONLYMATT AI Gateway (e.g., `https://your-app.onrender.com`)
- **Admin API Key**: Your API key for admin operations

> Utilisateurs om43.com : exécutez `./configure-om43.sh` (avec le chemin de WordPress en argument si nécessaire) pour pousser automatiquement `https://api.om43.com` et votre clé admin dans les options du site via WP-CLI.

### Optional Settings

- **Max Memory Items**: Maximum number of memory items to store (default: 100)
- **Enable Logging**: Enable debug logging for troubleshooting
- **Enable Widget**: Show chat widget on frontend pages

## Usage

### Shortcodes

Add these shortcodes to your pages or posts:

#### Chat Widget
```
[onlymatt_chat]
```
Displays an interactive chat widget where visitors can talk to the AI assistant.

#### Admin Panel (Admin Only)
```
[onlymatt_admin]
```
Displays a simplified admin panel for managing the AI system (requires admin privileges).

### Admin Dashboard

Access the full admin dashboard at:
- WordPress Admin → ONLYMATT AI

The dashboard includes:
- **Chat**: Direct interaction with the AI
- **Memory**: View and manage AI memory items
- **Tasks**: Create and track tasks
- **Reports**: Generate usage reports
- **Analysis**: Run data analysis
- **Settings**: Configure plugin options

## File Structure

```
onlymatt-wp-plugin/
├── onlymatt-ai.php              # Main plugin file
├── assets/
│   ├── css/
│   │   ├── admin.css           # Admin interface styles
│   │   └── frontend.css        # Frontend widget styles
│   └── js/
│       ├── admin.js            # Admin interface JavaScript
│       └── frontend.js         # Frontend widget JavaScript
└── templates/
    ├── admin-dashboard.php     # Main admin dashboard
    ├── admin-chat.php          # Chat interface
    ├── admin-tasks.php         # Task management
    ├── admin-settings.php      # Settings page
    ├── frontend-chat.php       # Frontend chat widget
    └── frontend-admin.php      # Frontend admin panel
```

## API Integration

The plugin communicates with your ONLYMATT AI Gateway via REST API calls to endpoints like:
- `/ai/chat` - Chat functionality
- `/ai/memory/*` - Memory management
- `/admin/tasks` - Task management
- `/admin/reports` - Report generation

## Security

- All AJAX requests include WordPress nonces for security
- Admin-only features require `manage_options` capability
- Input sanitization on all user inputs
- Secure API key storage using WordPress options

## Troubleshooting

### Common Issues

1. **"API connection error"**
   - Check your API Gateway URL in settings
   - Ensure your Render app is running
   - Check browser console for network errors

2. **Chat widget not appearing**
   - Verify the shortcode is added correctly
   - Check that the plugin is activated
   - Ensure JavaScript is enabled

3. **Admin features not working**
   - Confirm you have admin privileges
   - Check browser console for JavaScript errors
   - Verify AJAX endpoints are accessible

### Debug Mode

Enable debug logging in the plugin settings to get detailed error information in the WordPress debug log.

## Development

### Prerequisites

- WordPress 5.0+
- PHP 7.4+
- ONLYMATT AI Gateway (FastAPI application)

### Customization

The plugin is designed to be extensible. Key areas for customization:

- **Styling**: Modify CSS files in `assets/css/`
- **JavaScript**: Extend functionality in `assets/js/`
- **Templates**: Customize UI in `templates/`
- **API Calls**: Add new endpoints in the main plugin file

### Hooks and Filters

The plugin provides several WordPress hooks for customization:

- `onlymatt_before_chat` - Before sending chat message
- `onlymatt_after_chat` - After receiving chat response
- `onlymatt_api_response` - Filter API responses

## Support

For support and questions:
- Check the WordPress admin error logs
- Review browser console for JavaScript errors
- Ensure your ONLYMATT AI Gateway is properly configured

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### Version 1.0.0
- Initial release
- Basic chat functionality
- Memory management
- Task management
- Admin dashboard
- Shortcode integration