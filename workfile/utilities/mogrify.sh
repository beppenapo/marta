#!/usr/bin/env bash
mogrify -verbose -path "file/foto_small/" -shave 30x30 -gravity center -extent 150x150  "file/foto/*"
mogrify -verbose -path "file/foto_medium/" -shave 30x30 -gravity center -extent 250x250  "file/foto/*"
