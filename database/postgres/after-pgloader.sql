-- Run after pgloader has copied the restricted MariaDB dev dump into Neon.
-- Keep this file in Git; never commit the dump itself or connection URLs.

-- pgloader creates a schema named after the source MariaDB database. The app
-- uses PostgreSQL's default `public` schema, so replace the empty/interrupted
-- target schema and promote the imported schema before adding app triggers.
DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM information_schema.schemata WHERE schema_name = 'db_qlnckh') THEN
    DROP SCHEMA IF EXISTS public CASCADE;
    ALTER SCHEMA db_qlnckh RENAME TO public;
  END IF;
END;
$$;

CREATE OR REPLACE FUNCTION public.set_updated_at()
RETURNS trigger
LANGUAGE plpgsql
AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS tbl_project_payment_updated_at ON public.tbl_project_payment;
CREATE TRIGGER tbl_project_payment_updated_at
BEFORE UPDATE ON public.tbl_project_payment
FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();

DROP TRIGGER IF EXISTS tbl_scientific_curriculum_vitae_updated_at ON public.tbl_scientific_curriculum_vitae;
CREATE TRIGGER tbl_scientific_curriculum_vitae_updated_at
BEFORE UPDATE ON public.tbl_scientific_curriculum_vitae
FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();

DROP TRIGGER IF EXISTS tbl_system_setting_updated_at ON public.tbl_system_setting;
CREATE TRIGGER tbl_system_setting_updated_at
BEFORE UPDATE ON public.tbl_system_setting
FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();

-- pgloader usually resets sequences. This makes the operation explicit and
-- idempotent for every serial/identity column imported from the dump.
DO $$
DECLARE
  rec record;
  max_id bigint;
BEGIN
  FOR rec IN
    SELECT c.table_schema, c.table_name, c.column_name,
           pg_get_serial_sequence(format('%I.%I', c.table_schema, c.table_name), c.column_name) AS sequence_name
    FROM information_schema.columns c
    WHERE c.table_schema = 'public'
      AND c.column_default LIKE 'nextval%'
  LOOP
    EXECUTE format('SELECT max(%I) FROM %I.%I', rec.column_name, rec.table_schema, rec.table_name) INTO max_id;
    IF max_id IS NULL THEN
      EXECUTE format('SELECT setval(%L, 1, false)', rec.sequence_name);
    ELSE
      EXECUTE format('SELECT setval(%L, %s, true)', rec.sequence_name, max_id);
    END IF;
  END LOOP;
END;
$$;
