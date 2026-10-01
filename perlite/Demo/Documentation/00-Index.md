# Perlite Documentation

Perlite is a web based markdown viewer optimized for [Obsidian](https://obsidian.md/) notes. Put your Obsidian vault (or any markdown folder structure) on a web server and the page builds itself - no database, no build step, no converting. It's an open source alternative to [Obsidian Publish](https://obsidian.md/publish).

- Live demo: [perlite.secure77.de](https://perlite.secure77.de/)
- Source: [github.com/secure-77/Perlite](https://github.com/secure-77/Perlite)
- Community: [Perlite Discord Server](https://discord.gg/pkJ347ssWT)
- [Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)


## Features

- Navigation is built automatically from your vault folder structure
- No database required
- Obsidian themes, dark and light mode
- Fully responsive
- Interactive global and local graph
- LaTeX, Mermaid, callouts, footnotes, frontmatter
- Obsidian links, embeds, images, tags and link previews
- Read-only Obsidian Canvas support
- Search
- "Open in Obsidian" link


## How it works

Perlite consists of a few PHP files (`index.php`, `content.php`, `helper.php`) plus JS/CSS assets. Your vault is a folder that Perlite reads at request time. All server side settings are configured in a single `.env` file, all visitor side preferences are stored in the browser.

```
Perlite/
├── .env                  # your settings (copy of .env.example)
├── docker-compose.yml    # Docker setup
├── web/config/perlite.conf  # nginx config
└── perlite/              # the web root
    ├── index.php
    ├── content.php
    ├── helper.php
    ├── .js/ .styles/ .src/ vendor/
    └── Demo/             # a vault (this demo)
```


## Table of Contents

| # | Page | Content |
|---|------|---------|
| 1 | [Docker Setup](01-Docker-Setup.md) | Run Perlite with Docker Compose (recommended) |
| 2 | [Setup without Docker](02-Setup-without-Docker.md) | Run Perlite on your own nginx / Apache + PHP |
| 3 | [.env Settings](03-Env-Settings.md) | All server side settings explained |
| 4 | [Browser Settings](04-Browser-Settings.md) | Settings every visitor can change in the UI |
| 5 | [Obsidian Settings](05-Obsidian-Settings.md) | Required vault settings (relative links etc.) |
| 6 | [Graph](06-Graph.md) | Setting up the global and local graph |
| 7 | [Themes](07-Themes.md) | Using Obsidian themes |
| 8 | [Troubleshooting & FAQ](08-Troubleshooting-FAQ.md) | Common problems and questions |


## Quick Start

1. Read the [required Obsidian settings](05-Obsidian-Settings.md) - links and images won't work without them.
2. Pick a setup: [Docker](01-Docker-Setup.md) or [without Docker](02-Setup-without-Docker.md).
3. Copy `.env.example` to `.env` and set at least `NOTES_PATH` (see [.env Settings](03-Env-Settings.md)).
4. Optional: [set up the graph](06-Graph.md) and [themes](07-Themes.md).


## Security Notes

- Parsedown's safe mode (`HTML_SAFE_MODE`) is active by default, but Perlite is not meant to render untrusted user input.
- `.md` files and `metadata.json` should not be directly accessible via the browser - only PHP needs to read them. The provided nginx config (`web/config/perlite.conf`) takes care of that.
- `metadata.json` contains the structure of your whole vault, including files you may have excluded from the web server.
- Perlite has no access control. Use your web server or a reverse proxy if you need authentication, see [FAQ](08-Troubleshooting-FAQ.md).
