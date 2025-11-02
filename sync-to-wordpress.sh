#!/bin/bash
# Sync the canonical plugin in this repo to a local WordPress install.
# Usage: ./sync-to-wordpress.sh [--dry-run] [--safe] [--dest /path/to/wp-content/plugins/onlymatt-wp-plugin]

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_NAME="onlymatt-wp-plugin"
SRC_DIR="$SCRIPT_DIR"

DEST_DIR=""
DRY=""
RSYNC_DELETE="--delete"

while [[ $# -gt 0 ]]; do
	case "$1" in
		--dry-run)
			DRY="--dry-run"
			shift
			;;
		--safe)
			RSYNC_DELETE="--ignore-existing"
			shift
			;;
		--dest)
			DEST_DIR="$2"
			shift 2
			;;
		*)
			echo "Unknown argument: $1" >&2
			exit 1
			;;
	esac
done

if [[ -z "$DEST_DIR" ]]; then
	DEST_DIR="${WP_PLUGIN_DIR:-$HOME/Downloads/public_html/wp-content/plugins/$PLUGIN_NAME}"
fi

mkdir -p "$DEST_DIR"

echo "Syncing $SRC_DIR -> $DEST_DIR"

rsync \
	$DRY \
	-av --progress \
	$RSYNC_DELETE \
	--exclude '.git/' \
	--exclude '.github/' \
	--exclude 'sync-to-wordpress.sh' \
	"$SRC_DIR/" "$DEST_DIR/"

echo "Done."
