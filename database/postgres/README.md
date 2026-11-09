# Neon staging migration

`db_qlnckh.sql` is an intentionally untracked local input. It must be imported to
the local MariaDB development database before running:

```powershell
.\scripts\migrate-dev-dump-to-neon.ps1 -NeonDatabaseUrl '<Neon pooled DATABASE_URL>'
```

The script uses pgloader to translate the MariaDB schema/data into Neon, then
applies `after-pgloader.sql`. It preserves explicit IDs from the dump and resets
all imported serial sequences. Compare the row counts from `verify-import.sql`
against the MariaDB source before connecting Vercel.

After import, migrate records that refer to legacy local files to public Vercel
Blob URLs. The local files must be present in this checkout:

```powershell
$env:DATABASE_URL = '<Neon pooled DATABASE_URL>'
$env:BLOB_READ_WRITE_TOKEN = '<Vercel Blob token>'
python .\scripts\migrate-legacy-files-to-blob.py
```

Do not add `db_qlnckh.sql`, a Neon URL, or generated Blob URLs containing secrets
to Git. Existing legacy file paths remain historical records; all new uploads are
stored as public Vercel Blob URLs.
