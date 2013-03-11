#!/bin/bash

# cONFIG
SUFIJO="red"

# Absolute path to this script
RUTA=`readlink -f $0`
CARPETA=`dirname $RUTA`
EXEC_JS="$CARPETA/reduce_js.sh"
EXEC_CSS="$CARPETA/reduce_css.sh"

find -iname "*.$SUFIJO.js" -delete
find -iname "*.js" -exec $EXEC_JS {} $SUFIJO \;

find -iname "*.$SUFIJO.css" -delete
find -iname "*.css" -exec $EXEC_CSS {} $SUFIJO \;