# Troubleshooting & FAQ


## Troubleshooting

### Links or images don't work

- Check the [link format in Obsidian](05-Obsidian-Settings.md): it must be `Relative path to file` (or absolute with `ABSOLUTE_PATHS=true`).
- Existing links are not converted when you change the setting, use the Link Converter plugin.
- Linked files other than notes and images must be listed in `ALLOWED_FILE_LINK_TYPES`.
- If Perlite runs in a sub folder, check `URI_PATH` and the nginx `location`.

### The graph is missing

- The graph needs the `metadata.json` from the *Metadata Extractor* plugin in the **vault root**, see [Graph](06-Graph.md).
- Check that `TEMP_PATH` (default: system temp dir) is writable by PHP.

### Timeout on large vaults

The first build of the graph cache can exceed PHP's `max_execution_time`. Raise the limit or reload the page once the cache was written. Turning off *Orphans* in the graph settings speeds up rendering.

### Themes are not loaded

- The `.obsidian/themes` folder and `appearance.json` must be part of the published vault.
- The web server must allow access to these files, see [Themes](07-Themes.md).

### Settings in .env have no effect

- **Docker:** the `.env` must be next to `docker-compose.yml`, recreate the containers after changes: `docker compose up -d --force-recreate`.
- **No Docker:** the `.env` must be next to or inside the perlite folder. Real environment variables (e.g. from the PHP-FPM pool config) override the `.env`.
- Empty values use the default.

### Docker: still the old version after update

```bash
docker compose down
docker rmi sec77/perlite:latest
docker compose pull
docker compose up -d
```

### Docker: page not reachable

- Check that port 80 is open and not used by another service: `ss -tunlp`
- Test locally on the host: `curl 127.0.0.1:80`
- Make sure there is only one nginx config in `web/config/`.
- Check the logs: `docker compose logs`

### Docker: the demo vault is shown instead of my notes

`NOTES_PATH` must be the host path of your vault, e.g. `./MyVault` or `/srv/obsidian/MyVault`. The old value `NOTES_PATH=Demo` (before 1.6.3) doesn't work anymore, see [Docker Setup](01-Docker-Setup.md).


## FAQ

### How do I enable HTTPS?

Perlite itself only serves HTTP. Add TLS to your nginx config or put a reverse proxy (Traefik, Caddy, nginx, ...) in front of it.

### Can I protect Perlite with a password?

Not in Perlite itself. Use your web server (e.g. nginx `auth_basic`) or an authentication proxy like [Authentik](https://goauthentik.io/). Protecting single files or folders is not supported - hide them with `HIDE_FOLDERS` or don't publish them.

### Can I hide folders?

Yes, with `HIDE_FOLDERS` (comma separated folder names). Folders starting with `.` are always hidden. Keep in mind that hidden files are still part of `metadata.json`.

### Can I use HTML in my notes?

Set `HTML_SAFE_MODE=false`. Only do this if you trust the content of your vault. The README of the demo vault contains a small test for it.

### Are Obsidian plugins supported?

No. Perlite renders markdown, plugin specific syntax (e.g. Dataview) is shown as is.

### Can I set the size and alt text of images?

Yes: `![[image.png|Description|100x100]]`, see [Obsidian Settings](05-Obsidian-Settings.md).

### Why is there no perlite_web image on Docker Hub?

The web container uses the official `nginx` image with the config from `web/config/perlite.conf`, so it doesn't need to be maintained separately.


## Still stuck?

- Search the [GitHub issues](https://github.com/secure-77/Perlite/issues)
- Ask on the [Perlite Discord Server](https://discord.gg/pkJ347ssWT)
- Open a [new issue](https://github.com/secure-77/Perlite/issues/new)

---
Back to [Index](00-Index.md)
