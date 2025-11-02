#!/bin/bash
# Configure ONLYMATT plugin options for om43.com.
# Usage: ./configure-om43.sh [/absolute/path/to/wordpress]
# Requires: wp-cli installed and accessible as `wp`.

set -euo pipefail

WP_PATH="${1:-$HOME/Downloads/public_html}"
API_BASE="https://api.om43.com"
MAX_MEMORY="150"
ENABLE_LOGGING="0"
ENABLE_WIDGET="1"

if ! command -v wp >/dev/null 2>&1; then
  echo "Error: wp-cli not found in PATH." >&2
  exit 1
fi

if [[ ! -d "$WP_PATH" ]]; then
  echo "Error: WordPress path '$WP_PATH' does not exist." >&2
  exit 1
fi

read -r -s -p "Enter OM_ADMIN_KEY for om43.com: " OM_ADMIN_KEY
echo

if [[ -z "$OM_ADMIN_KEY" ]]; then
  echo "Error: OM_ADMIN_KEY cannot be empty." >&2
  exit 1
fi

WP="wp --path=$WP_PATH"

set_option() {
  local option_name="$1"
  local target_value="$2"
  local current_value
  current_value="$($WP option get "$option_name" 2>/dev/null || echo '')"
  if [[ "$current_value" == "$target_value" ]]; then
    echo "✔ $option_name already set" >&2
  else
    $WP option update "$option_name" "$target_value" >/dev/null
    echo "↺ Updated $option_name" >&2
  fi
}

set_option onlymatt_api_base "$API_BASE"
set_option onlymatt_admin_key "$OM_ADMIN_KEY"
set_option onlymatt_max_memory "$MAX_MEMORY"
set_option onlymatt_enable_logging "$ENABLE_LOGGING"
set_option onlymatt_enable_widget "$ENABLE_WIDGET"
set_option onlymatt_default_persona "web_developer"

cat <<EOM

ONLYMATT options verified/updated for om43.com
  - API Base: $API_BASE
  - Admin Key: (hidden)
  - Max Memory: $MAX_MEMORY
  - Logging Enabled: $ENABLE_LOGGING
  - Widget Enabled: $ENABLE_WIDGET
  - Default Persona: web_developer

If you need to refresh the WordPress cache, run: $WP cache flush
EOM
