#!/usr/bin/env bash
mogrify -verbose -path foto_small/ -shave 30x30 -thumbnail 150x150^ -gravity center -extent 150x150  foto/*.jpg
mogrify -verbose -path foto_medium/ -shave 30x30 -thumbnail 250x250^ -gravity center -extent 250x250  foto/*.jpg
