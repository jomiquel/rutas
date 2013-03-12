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

	read -p "Are you sure you want to remove $OUTFILE file (y/n)? " -n 1 -r
	if [[ $REPLY =~ ^[Yy]$ ]]
	then
    	rm "$OUTFILE"

    	echo >&1
    	echo "File has been removed." >&1

    	service apache2 reload

	fi

else

	echo "File $OUTFILE does not exist." >&2

fi