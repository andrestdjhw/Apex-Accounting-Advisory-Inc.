#!/bin/sh
# Production package of the theme (828 review, Priority 1): only runtime files.
# Usage: npm run bundle  ->  dist/apex_accounting_advisory_inc.zip
# Upload/replace the theme with this zip. Never upload node_modules, src or package files.
set -e
cd "$(dirname "$0")/.."
NAME=apex_accounting_advisory_inc
rm -rf dist
mkdir -p "dist/$NAME"
for item in style.css *.php .htaccess inc template-parts assets build; do
  if [ -e "$item" ]; then cp -R "$item" "dist/$NAME/"; fi
done
rm -f "dist/$NAME/build/"*.map
find "dist/$NAME" -name ".DS_Store" -delete
(cd dist && zip -qr "$NAME.zip" "$NAME")
echo "Created dist/$NAME.zip ($(du -h "dist/$NAME.zip" | cut -f1))"
