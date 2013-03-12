#!/bin/bash

# Absolute path to this script
RUTA=`readlink -f $0`
CARPETA=`dirname $RUTA`
CARPETA=`dirname $CARPETA`
ALIAS=`basename $CARPETA`
CARPETA="$CARPETA/code"

if [ -z "$1" ]; then
	echo "Using default value for alias: $ALIAS" >&1
else
	echo "Using param \$1 for alias: $1" >&1
	ALIAS=$1
fi

OUTFILE="/etc/apache2/conf.d/$ALIAS.conf"

if [ -f $OUTFILE ]; then

	echo "File $OUTFILE already exists!!" >&2

else

	echo "Creating $OUTFILE..." >&1

	echo "Alias /$ALIAS $CARPETA" > $OUTFILE

	echo "<Directory $CARPETA>" >> $OUTFILE
	echo "	Options FollowSymLinks +ExecCGI" >> $OUTFILE
	echo "	AddHandler cgi-script .cgi" >> $OUTFILE
	echo "</Directory>" >> $OUTFILE

	service apache2 reload

fi