begin;
alter table liste.adsp add column tooltip character varying;
UPDATE liste.adsp set tooltip = 'Le informazioni contenute nella scheda possono essere liberamente consultate da chiunque. E'' la situazione che si riscontra solitamente per i beni di proprietà pubblica.' where id = 1;
UPDATE liste.adsp set tooltip = 'La scheda contiene dati riservati per motivi di privacy.<br/>E'' la situazione che si riscontra in genere per i beni di proprietà privata, che possono contenere dati personali che non è opportuno divulgare.' where id = 2;
UPDATE liste.adsp set tooltip = 'La scheda contiene dati riservati per motivi di tutela.<br/>Si tratta di situazioni eccezionali per le quali, per particolari motivi di tutela individuati dall''Ente competente, non è opportuno divulgare informazioni di dettaglio sulla localizzazione del bene.' where id = 3;
commit;
