#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

# Build mapbox-gl:
cd "$DIR/mapbox-gl-js" || exit
npm install
npm run build-css
npm run build-prod-min

# Build frontend:
cd "$DIR/frontend" || exit
npm install
npm run build
