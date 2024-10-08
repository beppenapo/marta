#!/usr/bin/env bash
# Directory delle immagini originali e delle immagini modificate
source_dir="/var/www/html/marta/file/foto/"
medium_dir="/var/www/html/marta/file/foto_medium/"
small_dir="/var/www/html/marta/file/foto_small/"

# File di stato che tiene traccia delle immagini elaborate e i loro timestamp
state_file="/var/www/html/marta/workfile/utilities/processed_images.txt"

# Dimensioni massime per le immagini ottimizzate
max_width=1920
max_height=1080

# Loop attraverso tutti i file con spazi nel nome
find "$source_dir" -type f -name "* *" | while read -r file; do
  # Costruisce il nuovo nome del file rimuovendo gli spazi
  new_file=$(echo "$file" | tr -d ' ')
  
  # Rinomina il file
  mv "$file" "$new_file"
done

echo "Tutti gli spazi nei nomi dei file sono stati rimossi."
# Verificare se il file di stato esiste, altrimenti crearlo
if [ ! -f "$state_file" ]; then
  touch "$state_file"
fi

# Funzione per ottenere il timestamp di modifica del file
get_mod_time() {
  local file="$1"
  stat -c "%Y" "$file"
}

# Elaborare solo le nuove immagini o quelle modificate
#for img in "$source_dir"*.jpg; do
for img in "$source_dir"*; do
  # Estrai il nome del file senza la directory
  filename=$(basename "$img")

  # Ottieni il timestamp di modifica dell'immagine
  current_mod_time=$(get_mod_time "$img")

  # Cerca il file nel file di stato
  state_entry=$(grep "^$filename " "$state_file")

  if [ -z "$state_entry" ]; then
    # Il file non è presente nel file di stato, quindi processalo
    
    # Ottimizzare l'immagine per il web (ridimensionamento)
    mogrify -verbose -resize "${max_width}x${max_height}>" "$img"

    # Creare versioni ridotte
    mogrify -verbose -path "$small_dir" -shave 30x30 -thumbnail 150x150^ -gravity center -extent 150x150 "$img"
    mogrify -verbose -path "$medium_dir" -shave 30x30 -thumbnail 250x250^ -gravity center -extent 250x250 "$img"

    # Aggiungi il nome del file e il suo timestamp al file di stato
    echo "$filename $current_mod_time" >> "$state_file"
  else
    # Estrai il timestamp di modifica salvato dal file di stato
    saved_mod_time=$(echo "$state_entry" | awk '{print $2}')

    if [ "$current_mod_time" -ne "$saved_mod_time" ]; then
      # Il file è stato modificato, quindi processalo di nuovo

      # Ottimizzare l'immagine per il web (ridimensionamento)
      mogrify -verbose -resize "${max_width}x${max_height}>" "$img"

      # Creare versioni ridotte
      mogrify -verbose -path "$small_dir" -shave 30x30 -thumbnail 150x150^ -gravity center -extent 150x150 "$img"
      mogrify -verbose -path "$medium_dir" -shave 30x30 -thumbnail 250x250^ -gravity center -extent 250x250 "$img"

      # Aggiorna il file di stato con il nuovo timestamp
      sed -i "s/^$filename $saved_mod_time\$/$filename $current_mod_time/" "$state_file"
    else
      echo "L'immagine $filename non è stata modificata."
    fi
  fi
done
