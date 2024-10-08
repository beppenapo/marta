#!/bin/bash
for img in $(cat missing_in_medium.txt); do
    mogrify -verbose -path /var/www/html/marta/file/foto_medium/ -shave 30x30 -thumbnail 250x250^ -gravity center -extent 250x250 "/var/www/html/marta/file/foto/$img"
done