# Docker Setup

The easiest way to run Perlite. Docker Compose starts two containers:

| Container | Image | Purpose |
|-----------|-------|---------|
| `perlite` | [`sec77/perlite`](https://hub.docker.com/r/sec77/perlite) | PHP-FPM with the Perlite code, your vault is mounted read-only |
| `perlite_web` | `nginx:stable` (official) | Web server, serves Perlite on port 80 |


## Requirements

- Docker with the Compose plugin (`docker compose`)
- From this repository you need:
  - `docker-compose.yml`
  - `web/config/perlite.conf` (nginx config)
  - `.env.example`

The easiest way is to clone the repository:

```bash
git clone https://github.com/secure-77/Perlite.git
cd Perlite
```


## 1. Create the .env file

```bash
cp .env.example .env
```

The `.env` file has to be located next to `docker-compose.yml`. It is used by Docker Compose and passed to the Perlite container.


## 2. Set the vault path

Set `NOTES_PATH` in the `.env` to the folder of your vault on the **host**. The path can be relative to the compose file or absolute:

```ini
# the demo vault
NOTES_PATH=./perlite/Demo

# or your own vault
NOTES_PATH=/srv/obsidian/MyVault
```

Docker Compose mounts this folder read-only to `/var/www/perlite/Notes` inside the container. You don't need to change the volumes in `docker-compose.yml`.

Optionally set the vault name shown in the UI (and used for the "Open in Obsidian" link):

```ini
VAULT_NAME=MyVault
```

All other settings are described in [.env Settings](03-Env-Settings.md).


## 3. Start Perlite

```bash
docker compose up -d
```

Perlite is now available at `http://localhost` (port 80). To use another port, change the `ports` of the `web` service in `docker-compose.yml`, e.g. `8080:80`.

After changing the `.env`, recreate the containers:

```bash
docker compose up -d --force-recreate
```


## Updating

```bash
docker compose pull
docker compose up -d
```

If you still get an old version, remove the local images and pull again:

```bash
docker compose down
docker rmi sec77/perlite:latest
docker compose pull
docker compose up -d
```

Also check the [Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md) for changes of `perlite.conf` or the `.env` settings.

> [!warning] Upgrading from 1.6.2 or older
> The vault path is now only set via `NOTES_PATH` in the `.env` as host path. Existing `.env` files with `NOTES_PATH=Demo` have to be changed to `NOTES_PATH=./perlite/Demo` (or the path of your vault). Custom volume mounts for the vault in `docker-compose.yml` are not needed anymore.


## Dev image

`docker-compose-dev.yml` uses the `sec77/perlite:dev` image, which contains the latest changes of the dev branch:

```bash
docker compose -f docker-compose-dev.yml up -d
```


## HTTPS

The containers only serve plain HTTP. Put a reverse proxy (e.g. Traefik, Caddy, nginx) with TLS in front of it, see [FAQ](08-Troubleshooting-FAQ.md).


## Next Steps

- [Required Obsidian settings](05-Obsidian-Settings.md)
- [Graph setup](06-Graph.md)
- [Themes](07-Themes.md)

---
Back to [Index](00-Index.md)
