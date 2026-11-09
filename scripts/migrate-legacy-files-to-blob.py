"""One-time staging migration for local asset/file and asset/img/upload records.

Run only after Neon import, from a checkout that contains the legacy files:
  DATABASE_URL=... BLOB_READ_WRITE_TOKEN=... python scripts/migrate-legacy-files-to-blob.py
"""

import json
import mimetypes
import os
import secrets
from pathlib import Path
from urllib.parse import quote
from urllib.request import Request, urlopen

import psycopg

ROOT = Path(__file__).resolve().parents[1]


def blob_url(relative_path: str) -> str:
    source = (ROOT / relative_path.lstrip('/')).resolve()
    if not source.is_file() or ROOT not in source.parents:
        raise FileNotFoundError(relative_path)
    pathname = f'legacy/{secrets.token_hex(12)}-{source.name}'
    request = Request(
        f'https://vercel.com/api/blob/?pathname={quote(pathname)}',
        data=source.read_bytes(),
        method='PUT',
        headers={
            'Authorization': f"Bearer {os.environ['BLOB_READ_WRITE_TOKEN']}",
            'x-api-version': '13',
            'x-vercel-blob-access': 'public',
            'x-add-random-suffix': '1',
            'x-content-type': mimetypes.guess_type(source.name)[0] or 'application/octet-stream',
        },
    )
    with urlopen(request, timeout=60) as response:
        return json.loads(response.read())['url']


def migrate_column(cursor, table: str, key: str, column: str) -> int:
    cursor.execute(f'SELECT {key}, {column} FROM {table} WHERE {column} IS NOT NULL')
    changed = 0
    for record_id, path in cursor.fetchall():
        if not path or str(path).startswith('https://'):
            continue
        try:
            cursor.execute(f'UPDATE {table} SET {column} = %s WHERE {key} = %s', (blob_url(path), record_id))
            changed += 1
        except FileNotFoundError:
            print(f'SKIP missing local file: {path}')
    return changed


def migrate_guides(cursor) -> int:
    cursor.execute('SELECT id, file_guides FROM tbl_guides WHERE file_guides IS NOT NULL')
    changed = 0
    for guide_id, raw_files in cursor.fetchall():
        files = json.loads(raw_files or '[]')
        dirty = False
        for file in files:
            path = file.get('file_path')
            if path and not path.startswith('https://'):
                try:
                    file['file_path'] = blob_url(path)
                    dirty = True
                except FileNotFoundError:
                    print(f'SKIP missing local file: {path}')
        if dirty:
            cursor.execute('UPDATE tbl_guides SET file_guides = %s WHERE id = %s', (json.dumps(files, ensure_ascii=False), guide_id))
            changed += 1
    return changed


with psycopg.connect(os.environ['DATABASE_URL']) as connection:
    with connection.cursor() as cursor:
        count = 0
        count += migrate_column(cursor, 'tbl_project_file', 'file_id', 'file_path')
        count += migrate_column(cursor, 'tbl_project_payment', 'payment_id', 'file_path')
        count += migrate_column(cursor, 'tbl_user', 'user_id', 'avatar')
        count += migrate_column(cursor, 'tbl_system_setting', 'id', 'logo_path')
        count += migrate_column(cursor, 'tbl_scientific_curriculum_vitae', 'cv_id', 'cv_file_path')
        count += migrate_guides(cursor)
    connection.commit()

print(f'Migrated {count} database records to public Vercel Blob URLs.')
