#!/bin/bash
# Sync local Documents copy to WP plugin directory (be careful: can overwrite)
# Usage: ./sync-to-wordpress.sh [--dry-run] [--safe]
WP_PLUGIN_DIR="$HOME/Downloads/public_html/wp-content/plugins/onlymatt-wp-plugin"
SRC_DIR="$HOME/Documents/onlymatt-wp-plugin/"
if [ "$1" = "--dry-run" ]; then DRY="--dry-run"; else DRY=""; fi
if [ "$1" = "--safe" ]; then RSYNC_OPTS="-av --progress --ignore-existing --exclude=.git"; else RSYNC_OPTS="-av --progress --delete --exclude=.git"; fi
mkdir -p "$WP_PLUGIN_DIR"
echo "Syncing from $SRC_DIR to $WP_PLUGIN_DIR"
rsync $DRY $RSYNC_OPTS "$SRC_DIR" "$WP_PLUGIN_DIR"
