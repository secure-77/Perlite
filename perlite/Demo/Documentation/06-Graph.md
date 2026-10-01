# Graph

Perlite shows an interactive graph of your notes, similar to Obsidian:

- **Global graph** - all notes and their links, opened with the graph icon in the left ribbon
- **Local graph** - the current note and its direct links, shown in the right panel

Perlite does not parse the links of your whole vault itself. The graph is built from a `metadata.json` file, which is created by an Obsidian plugin. **Without this file there is no graph** (and no "random note" button).


## Setup

### 1. Install the Metadata Extractor plugin

In Obsidian open **Settings → Community plugins → Browse**, search for **Metadata Extractor** and install and enable it.

### 2. Configure the plugin

In the plugin settings:

- set the path of the `metadata.json` to the **root folder of your vault**, e.g. `C:\Users\John\MyNotes\metadata.json`
- set how often the file is written (interval), and/or let it be written when Obsidian starts

Perlite only needs the `metadata.json`, the other export files of the plugin are not used.

### 3. Publish it with your vault

The `metadata.json` has to be located in the vault root on the server, next to your notes:

```
MyNotes/
├── .obsidian/
├── metadata.json
├── README.md
└── ...
```

If you sync or copy your vault to the server, make sure the `metadata.json` is included.


## Caching

Building the graph from a large `metadata.json` takes time, so Perlite caches the result in `TEMP_PATH` (default: the system temp dir):

```
metadata_<vault folder>.temp
metadata_<vault folder>.md5
```

The cache is rebuilt automatically when `metadata.json` changes. The temp folder must be writable by PHP. Only notes which really exist in the vault on the server are shown, so excluded files are not visible in the graph.


## Performance

- For large vaults the global graph can be slow. Turning off **Orphans** in the graph settings speeds it up a lot.
- With very many files the first build of the cache can run into a PHP timeout. Open the graph once more after the cache was written, or raise `max_execution_time` in PHP.


## Settings

Visitors can change the look of the graph (orphans, tags, node size, link distance, edge style, ...), see [Browser Settings](04-Browser-Settings.md).

The graph is rendered with [vis-network](https://visjs.github.io/vis-network/docs/network/). For further adjustments (physics, colors, layout) edit the `options` object in `perlite/.js/perlite.js`.


## Security

`metadata.json` contains the structure of your whole vault, including files and folders you didn't publish. Block direct access to it in your web server - the provided nginx config denies all `.json` files except `appearance.json`.

---
Back to [Index](00-Index.md)
