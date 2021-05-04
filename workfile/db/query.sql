SELECT scheda.id, scheda.titolo, cm.cmpd AS data_ins, (utenti.cognome || ' ' || utenti.nome) AS compilatore
FROM scheda
JOIN utenti ON utenti.id = cm.cmpn
JOIN cm ON cm.scheda = scheda.id
WHERE scheda.tipo = ". $tipo."
  AND (scheda.titolo ILIKE '%".$search."%' OR TO_CHAR(cm.cmpd, 'YYYY-MM-DD') ILIKE '%".$search."%' OR utenti.cognome ILIKE '%".$search."%' OR utenti.nome ILIKE '%".$search."%')". $filtroUt."
ORDER BY data_ins DESC;
