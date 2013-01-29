#!/bin/bash
while IFS=\; read col1 col2 col3
do

	if [ "" == "$ES_FILE" ]; then
		
		ES_FILE="${col1}"
		EN_FILE="${col2}"

		echo "<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');" > $ES_FILE 
		echo >> $ES_FILE
		echo >> $ES_FILE

		echo "<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');" > $EN_FILE
		echo >> $EN_FILE
		echo >> $EN_FILE

	else

		if [ "" == "${col1}" ]; then
			echo >> $ES_FILE
			echo "/**" >> $ES_FILE
			echo " *   Sección ${col3}" >> $ES_FILE
			echo " */ " >> $ES_FILE

			echo >> $EN_FILE
			echo "/**" >> $EN_FILE
			echo " *   Sección ${col3}" >> $EN_FILE
			echo " */ " >> $EN_FILE

		else

			echo "\$lang['${col1}'] = '${col2}';" >> $ES_FILE
  			echo "\$lang['${col1}'] = '${col3}';" >> $EN_FILE
	  	fi
  	fi
done < languages.csv

echo >> $ES_FILE
echo >> $ES_FILE
echo "/*** End of file $ES_FILE ***/" >> $ES_FILE

echo >> $EN_FILE
echo >> $EN_FILE
echo "/*** End of file $EN_FILE ***/" >> $EN_FILE