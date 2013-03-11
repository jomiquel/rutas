#!/bin/bash

# Config
SUFIJO="$2"

# Absolute path to this script
SCRIPT=`readlink -f $0`
RUTA_SCRIPT=`dirname $SCRIPT`
EXEC="$RUTA_SCRIPT/reduce_css.php"

FILE=`readlink -f $1`
CARPETA=`dirname $FILE`

filename=`basename $FILE`
extension="${filename##*.}"
namealone="${filename%.*}"

O_FILE="$CARPETA/$namealone.$SUFIJO.$extension"

php -f $EXEC $1 > $O_FILE