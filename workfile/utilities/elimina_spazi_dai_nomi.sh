#!/bin/bash

# Directory che contiene i file
source_dir="/var/www/html/marta/file/foto/"

# Loop attraverso tutti i file con spazi nel nome
find "$source_dir" -type f -name "* *" | while read -r file; do
  # Costruisce il nuovo nome del file rimuovendo gli spazi
  new_file=$(echo "$file" | tr -d ' ')
  
  # Rinomina il file
  mv "$file" "$new_file"
done

echo "Tutti gli spazi nei nomi dei file sono stati rimossi."
