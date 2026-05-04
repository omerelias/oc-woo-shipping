#!/usr/bin/env bash
# Regenerate ocws.pot from PHP/JS in this plugin only (no backup forks, no translation outputs).
# Usage (SSH): cd .../wp-content/plugins/oc-woo-shipping && bash languages/update-i18n.sh

set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

# Paths relative to plugin root. "languages" = skip .po/.pot/.mo/.l10n.php noise in catalog.
EXCLUDE=".git,node_modules,vendor,languages,admin/class-oc-woo-shipping-admin-bkup.php,includes/class-oc-woo-shipping-slots-copy.php,includes/class-oc-woo-shipping-slots-copy-2.php,includes/class-ocws-admin-columns1.php,public/class-oc-woo-shipping-public-ariel.php,templates/cart-shipping-ariel.php"

wp i18n make-pot . languages/ocws.pot --domain=ocws --exclude="$EXCLUDE"

msgmerge --update --backup=none languages/ocws-he_IL.po languages/ocws.pot
msgmerge --update --backup=none languages/ocws-en_US.po languages/ocws.pot

msgfmt -o languages/ocws-he_IL.mo languages/ocws-he_IL.po
msgfmt -o languages/ocws-en_US.mo languages/ocws-en_US.po

echo "Done: ocws.pot + PO/MO updated (excludes backup/unused forks)."
