ALTER TABLE public.scheda
    ALTER COLUMN nctn DROP NOT NULL;

ALTER TABLE public.scheda
    ALTER COLUMN inventario DROP NOT NULL;

ALTER TABLE public.scheda
    ADD COLUMN titolo text;

ALTER TABLE public.scheda
    ADD COLUMN tipo integer NOT NULL;

ALTER TABLE public.lc
    ALTER COLUMN ldcs DROP NOT NULL;