#!/bin/bash

# Absolute path to this script
RUTA=`readlink -f $1`
CARPETA=`dirname $RUTA`

filename=`basename $RUTA`
extension="${filename##*.}"
namealone="${filename%.*}"

O_FILE="$CARPETA/$namealone.min.$extension"

wine ~/Descargas/jsmin.exe < $1 > $O_FILE