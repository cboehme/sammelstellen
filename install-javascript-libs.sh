#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

cp "$DIR/frontend/dist/frontend.js" "$DIR/wordpress-plugin/_inc/"
cp "$DIR/mapbox-gl-js/dist/mapbox-gl.js" "$DIR/wordpress-plugin/_inc/"
cp "$DIR/mapbox-gl-js/dist/mapbox-gl.css" "$DIR/wordpress-plugin/_inc/"
