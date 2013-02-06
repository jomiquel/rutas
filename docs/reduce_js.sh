#!/bin/bash

# Absolute path to this script
RUTA=`readlink -f $0`
CARPETA=`dirname $RUTA`
EXEC="$CARPETA/minjsname.sh"

find -iname "*.js" -exec $EXEC {} \;
find -iname "*.css" -exec $EXEC {} \;