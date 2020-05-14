#!/usr/bin/env bash

ZIPFILE="sammelstellen-${1}.zip"

rm -f $ZIPFILE
ln -sf wordpress-plugin Sammelstellen
zip -r $ZIPFILE Sammelstellen -x Sammelstellen/.git/\* Sammelstellen/.idea/\*
rm Sammelstellen