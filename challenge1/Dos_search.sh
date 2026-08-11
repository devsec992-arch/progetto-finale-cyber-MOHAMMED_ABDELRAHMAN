#!/bin/bash

# Variabile URL del target protetto
URL="http://cyber.blog:8000/articles/search"

echo "=== Avvio Test Rate Limiting su $URL ==="

# Esegue 500 richieste in parallelo
for i in {1..500}
do
   curl -s -o /dev/null -w "Richiesta $i -> HTTP Status: %{http_code}\n" \
        "$URL?query=TEST_ATTACCO_STRINGA_LUNGA" &
done

wait
echo "=== Test Completato ==="

