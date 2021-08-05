#!/usr/bin/env bash
  read -r -p 'da che giorno? formato data: aaaa-mm-dd: ' da
  read -r -p 'a che giorno? formato data: aaaa-mm-dd:' a
  git log --pretty=format:"%ad - %an: %s" --after="${da}" --until="${a}"
