# .env Settings

All server side settings of Perlite are configured in a single `.env` file. Start with a copy of [`.env.example`](https://github.com/secure-77/Perlite/blob/main/.env.example):

```bash
cp .env.example .env
```

Where the file is located depends on your setup:

- **Docker:** next to `docker-compose.yml`, it is passed to the Perlite container - see [Docker Setup](01-Docker-Setup.md)
- **No Docker:** next to the perlite folder (preferred) or inside it - see [Setup without Docker](02-Setup-without-Docker.md)


## Syntax

```ini
# comment
KEY=value
SITE_DESC="value with spaces"
```

- One `KEY=value` per line, lines starting with `#` are comments.
- Values can be quoted with `"` or `'`. Unquoted values may have a trailing ` # comment`.
- **Empty values fall back to the default.** A setting that is missing or empty uses the default listed below.
- Booleans: `true` / `false` (also `1` / `0`, `yes` / `no`, `on` / `off`).
- Lists: comma separated, e.g. `pdf,mp4`.
- Real environment variables take precedence over the `.env` file.


## General Settings

| Setting | Default | Description |
|---------|---------|-------------|
| `NOTES_PATH` | `Demo` | Path to your vault. **Docker:** host path, relative to the compose file or absolute (e.g. `./perlite/Demo`, `/srv/obsidian/MyVault`). **No Docker:** folder inside the perlite folder (e.g. `MyNotes` or `./perlite/MyNotes`). |
| `VAULT_NAME` | folder name of the vault (`Notes` with Docker) | Vault name shown in the UI. Also used for the "Open in Obsidian" link, so it should match the name of the vault in Obsidian. |
| `HOME_FILE` | `README` | Start page, path of a note relative to the vault root, without `.md`. |
| `SITE_TITLE` | `Perlite` | Browser tab title. |


## Frontend Settings

These are the defaults for every visitor. Some of them can be changed by the visitor in the [Browser Settings](04-Browser-Settings.md).

| Setting | Default | Description |
|---------|---------|-------------|
| `LINE_BREAKS` | `true` | Render single line breaks as `<br>` (like Obsidian's "Strict line breaks" off). |
| `DISABLE_POP_HOVER` | `false` | Disable the link preview popups on hover. |
| `SHOW_TOC` | `true` | Show the table of contents in the right panel. |
| `SHOW_LOCAL_GRAPH` | `true` | Show the local graph in the right panel. |
| `FONT_SIZE` | `15` | Default font size in px. |
| `HIDE_FOLDERS` | *(none)* | Comma separated list of folder names to hide, e.g. `docs,private,trash`. Matches the folder name on any level (case sensitive). Folders starting with `.` are always hidden. |
| `NICE_LINKS` | `true` | Show only the file name instead of the full path in internal links. |
| `TREE_VISIBLE_EXTENSIONS` | *(none)* | Comma separated list of file types shown in the navigation next to the notes, e.g. `pdf,docx`. Clicking them downloads the file. |


## Advanced Settings

| Setting | Default | Description |
|---------|---------|-------------|
| `HIDDEN_FILE_ACCESS` | `false` | Allow access to notes in hidden folders (`HIDE_FOLDERS` and dot folders). They stay hidden in the navigation but can be opened via links. |
| `ABSOLUTE_PATHS` | `false` | Set to `true` if your vault uses absolute paths for links (see [Obsidian Settings](05-Obsidian-Settings.md)). |
| `URI_PATH` | `/` | URL path Perlite is served from, e.g. `/perlite/`. Needs a leading and trailing `/`. |
| `HTML_SAFE_MODE` | `true` | Escape raw HTML in notes. Only disable it if you trust all content of your vault. |
| `ZETTELKASTEN_FILENAMES_ENABLED` | `false` | Show the frontmatter `title` or the first H1 instead of the file name. Use with caution. |
| `HIGHLIGHTJS_LANGS` | `powershell` | Comma separated list of additional [highlight.js languages](https://github.com/highlightjs/highlight.js/blob/main/SUPPORTED_LANGUAGES.md) that are not in the default bundle, e.g. `powershell,x86asm`. Loaded from cdnjs. |
| `ALLOWED_FILE_LINK_TYPES` | `pdf,mp4` | Comma separated list of file types that can be linked / embedded in notes. |
| `CANVAS_IFRAME_EMBEDS` | `click` | Embed websites of canvas link cards as iframe: `off`, `click` (load on click) or `auto`. YouTube and Vimeo links are always embedded. Many websites don't allow being embedded. |
| `TEMP_PATH` | system temp dir | Folder for the cached graph relations. Must be writable by PHP. See [Graph](06-Graph.md). |


## Metadata Settings

Used for the OpenGraph / Twitter meta tags (link previews in chats and social media).

| Setting | Default | Description |
|---------|---------|-------------|
| `SITE_TYPE` | `article` | `og:type` |
| `SITE_IMAGE` | Perlite screenshot | `og:image`, URL of the preview image |
| `SITE_URL` | `https://perlite.secure77.de` | `og:url` |
| `SITE_DESC` | `A web based markdown viewer optimized for Obsidian Notes` | `og:description`, also shown in the profile section |
| `SITE_NAME` | `Perlite Demo` | `og:site_name` |


## Profile Settings

The profile section is shown at the bottom of the left panel.

| Setting | Default | Description |
|---------|---------|-------------|
| `SITE_LOGO` | *(none)* | Logo file inside the perlite folder, e.g. `perlite.svg`. **Empty = no profile section.** |
| `SITE_HOMEPAGE` | value of `SITE_URL` | Homepage link. |
| `SITE_GITHUB` | *(none)* | GitHub link, empty for none. |
| `SITE_TWITTER` | *(none)* | X / Twitter handle incl. `@`, e.g. `@secure_sec77`, empty for none. |


## Example

```ini
NOTES_PATH=/srv/obsidian/MyVault
VAULT_NAME=MyVault
HOME_FILE=Welcome
SITE_TITLE=My Notes
HIDE_FOLDERS=private,templates
TREE_VISIBLE_EXTENSIONS=pdf
SITE_URL=https://notes.example.com
SITE_NAME=My Notes
SITE_DESC="My personal knowledge base"
```

---
Back to [Index](00-Index.md)
