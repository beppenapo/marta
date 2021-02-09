#!/usr/bin/env bash
rsync -varzhP --delete /var/www/html/marta/ beppe@91.121.82.80:/var/www/marta/
