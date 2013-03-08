#!/bin/bash

# Absolute path to this script
RUTA=`readlink -f $0`
CARPETA=`dirname $RUTA`

#col1: identificador del texto.
#col2: texto en español
#col3: texto en inglés
#col4: Si existe, es que no debe incluirse en js

while IFS=\; read col1 col2 col3 col4
do

	#siempre debe existir columna 3. Si no, la línea se ignora.
	if [ "" != "${col3}" ]; then


		if [ "" == "$ES_PHP_FILE" ]; then
			
			ES_PHP_FILE="${col2}"
			EN_PHP_FILE="${col3}"

			echo $ES_PHP_FILE
			echo $EN_PHP_FILE

			echo "<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');" > $ES_PHP_FILE 
			echo >> $ES_PHP_FILE
			echo >> $ES_PHP_FILE

			echo "<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');" > $EN_PHP_FILE
			echo >> $EN_PHP_FILE
			echo >> $EN_PHP_FILE

		else

			if [ "" == "$ES_JS_FILE" ]; then

				ES_JS_FILE="${col2}"
				EN_JS_FILE="${col3}"

				echo $ES_JS_FILE
				echo $EN_JS_FILE

				echo "var getLanguage = function(key) {	line = { " > $ES_JS_FILE 
				echo "var getLanguage = function(key) {	line = { " > $EN_JS_FILE 

			else

				if [ "" == "${col1}" ]; then

					echo >> $ES_PHP_FILE
					echo "/**" >> $ES_PHP_FILE
					echo " *   Sección ${col3}" >> $ES_PHP_FILE
					echo " */ " >> $ES_PHP_FILE

					echo >> $EN_PHP_FILE
					echo "/**" >> $EN_PHP_FILE
					echo " *   Sección ${col3}" >> $EN_PHP_FILE
					echo " */ " >> $EN_PHP_FILE

				else

					echo "\$lang['${col1}'] = '${col2}';" >> $ES_PHP_FILE
		  			echo "\$lang['${col1}'] = '${col3}';" >> $EN_PHP_FILE

					if [ "" == "${col4}" ]; then
						echo "'${col1}':'${col2}'," >> $ES_JS_FILE
						echo "'${col1}' : '${col3}'," >> $EN_JS_FILE
					fi

			  	fi
			fi
	  	fi

	fi
done < "$CARPETA"/languages.csv

echo >> $ES_PHP_FILE
echo >> $ES_PHP_FILE
echo "/*** End of file $ES_PHP_FILE ***/" >> $ES_PHP_FILE
echo "};return line[key];};" >> $ES_JS_FILE

echo >> $EN_PHP_FILE
echo >> $EN_PHP_FILE
echo "/*** End of file $EN_PHP_FILE ***/" >> $EN_PHP_FILE
echo "};return line[key];};" >> $EN_JS_FILE
