begin;
drop view if exists schede;
create view schede as 
WITH main AS (
         SELECT s.id AS scheda,
            s.titolo,
            s.tsk,
            tsk.value AS tipo,
            s.cmpn,
            concat(u.nome, ' ', u.cognome) AS operatore,
            nctn.nctn,
            concat(i.prefisso, ' ', i.inventario, ' ', i.suffisso) AS inventario
           FROM scheda s
             JOIN liste.tsk tsk ON s.tsk = tsk.id
             JOIN utenti u ON s.cmpn = u.id
             JOIN nctn_scheda nctn ON nctn.scheda = s.id
             LEFT JOIN inventario i ON i.scheda = s.id
        ), stato AS (
         SELECT stato_scheda.scheda,
            stato_scheda.chiusa,
            stato_scheda.verificata,
            stato_scheda.inviata,
            stato_scheda.accettata
           FROM stato_scheda
        ), ogtd AS (
         SELECT og_nu.scheda,
            ogtd_1.id AS ogtdid,
            ogtd_1.value AS ogtd
           FROM og_nu
             JOIN liste.ogtd ogtd_1 ON og_nu.ogtd = ogtd_1.id
        UNION
         SELECT og_ra.scheda,
            ogtd_1.id AS ogtdid,
            ogtd_1.value AS ogtd
           FROM og_ra
             JOIN liste.ra_cls_l4 ogtd_1 ON og_ra.l4 = ogtd_1.id
        ), mtc AS (
         SELECT mtc_1.scheda,
            l.value as materia,
            mtc_1.tecnica
         FROM public.mtc mtc_1
         join liste.materia l on mtc_1.materia = l.id  
        ), cronologia AS (
         SELECT dt.scheda,
            dt.dtzgi,
            dt.dtzgf,
            inizio.value AS inizio,
            fine.value AS fine
           FROM dt
             JOIN liste.cronologia inizio ON dt.dtzgi = inizio.id
             JOIN liste.cronologia fine ON dt.dtzgf = fine.id
        ), ubicazione AS (
         SELECT lc.scheda,
            lc.piano,
            lc.sala,
            lc.contenitore,
            lc.cassetta,
            s.descrizione AS nome_sala
           FROM lc,
            liste.sale s
          WHERE lc.sala = s.id
        ), localizzazione AS (
         SELECT s.scheda,
            s.comune AS comid,
            s.via,
            s.geonote,
            c.comune
           FROM geolocalizzazione s
             JOIN comuni c ON s.comune = c.id
        )
 SELECT DISTINCT main.scheda,
    main.nctn,
    main.tsk,
    main.tipo,
    main.inventario,
    main.titolo,
    main.cmpn,
    main.operatore,
    stato.chiusa,
    stato.verificata,
    stato.inviata,
    stato.accettata,
    ogtd.ogtd,
    mtc.materia,
    mtc.tecnica,
    cronologia.inizio,
    cronologia.fine,
    ubicazione.piano,
    ubicazione.sala,
    ubicazione.contenitore,
    ubicazione.cassetta,
    localizzazione.comune,
    localizzazione.via
   FROM main
     JOIN stato ON stato.scheda = main.scheda
     JOIN ogtd ON ogtd.scheda = main.scheda
     JOIN mtc ON mtc.scheda = main.scheda
     JOIN cronologia ON cronologia.scheda = main.scheda
     JOIN ubicazione ON ubicazione.scheda = main.scheda
     LEFT JOIN localizzazione ON localizzazione.scheda = main.scheda
 order by 1 asc;
 alter view schede owner to marta;
 commit;