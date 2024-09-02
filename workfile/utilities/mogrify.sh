#!/usr/bin/env bash
mogrify -verbose -path "/var/www/marta/file/foto_small/" -shave 30x30 -thumbnail 150x150^ -gravity center -extent 150x150 "/var/www/marta/file/foto/*.jpg"
echo "foto_small aggiornato"
mogrify -verbose -path "/var/www/marta/file/foto_medium/" -shave 30x30 -thumbnail 250x250^ -gravity center -extent 250x250 "/var/www/marta/file/foto/*.jpg"
echo "foto_medium aggiornato"
