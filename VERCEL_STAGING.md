# Private Vercel staging runbook

The linked Vercel project is `qlnckh-private-staging`. Do not deploy it until
the following prerequisites are complete.

1. Create a Neon database through the Vercel Marketplace and keep its pooled
   PostgreSQL URL as `DATABASE_URL` (with `sslmode=require`).
2. Create a public Vercel Blob store and set `BLOB_READ_WRITE_TOKEN`.
3. Create an Ably app, then set `ABLY_API_KEY` to its server-side API key. Never
   expose this value in browser code; browsers call `detect=ably_token` instead.
4. Generate a unique long `JWT_SECRET` for staging. Do not reuse the previous
   hard-coded local secret.
5. Add all four values to the Preview environment with `vercel env add` (and
   Production only when this staging deployment is intentionally promoted).
6. In Vercel Dashboard, enable Deployment Protection / Vercel Authentication
   for the project before sharing its URL.

Run the restricted database import and count checks described in
[`database/postgres/README.md`](database/postgres/README.md), including the
legacy Blob file migration. Only then create the preview:

```powershell
vercel deploy
```

Verify `/QLNCKH/index.php`, `/QLNCKH/dashboard`, login, role/user management,
project/CV/task/payment updates, uploads, Excel export and Ably delivery. Roll
back by promoting the prior Vercel deployment and restoring the Neon branch or
backup. Blob objects are intentionally public: anybody holding a Blob URL can
read that file.

`vercel build` on this Windows machine fails inside the third-party PHP runtime
because it expects Linux runtime paths (`php/php`). The Vercel deployment build
runs on Linux, so use its build log as the source of truth for this runtime.
