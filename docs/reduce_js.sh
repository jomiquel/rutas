#!/bin/bash

# Config
SUFIJO="$2"

# Absolute path to this script
RUTA=`readlink -f $1`
CARPETA=`dirname $RUTA`

filename=`basename $RUTA`
extension="${filename##*.}"
namealone="${filename%.*}"

O_FILE="$CARPETA/$namealone.$SUFIJO.$extension"

jsmin < $1 > $O_FILE