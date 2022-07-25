# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


	
A web based markdown viewer optimized for [Obsidian](https://obsidian.md/) Notes

Just put your whole Obsidian vault or markdown folder/file structure in your web directory. The page builds itself. 

Its an open source alternative to  [obisidian publish](https://obsidian.md/publish).

Read more about Perlite and staging tips on my blog post: [Perlite on Secure77](https://secure77.de/perlite).

If you want to discuss about Perlite you can join the thread in the [Obsidian Forum](https://forum.obsidian.md/t/perlite-publish-your-notes-to-your-own-web-server/21712) or join the [Perlite Discord Server](https://discord.gg/pkJ347ssWT)

## Demo

[Perlite Demo](https://perlite.secure77.de/)


![Demo Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/screenshot.png "Demo Screenshot")

![Graph Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/graph.png "Graph Screenshot")

## Features

- Auto build up, based on your folder (vault) structure
- Fullscreen mode
- Full responsive
- No manual parsing or converting necessary
- Full interactive Graph
- LaTeX support
- Mermaid support
- Search
- Support Obisdian links and images
- [![coverage](https://img.shields.io/badge/Bootswatch-Themes-blue)](https://bootswatch.com) (Slate)

## Changelog
[Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)


## Install
Just put the content of the perlite directory in your web root directory, your notes should resident as a subfolder of perlite.

For Docker just check the [DOCKER](https://github.com/secure-77/Perlite/blob/main/Docker.md) readme.

### Requirements
- Web server, tested with ![coverage](https://img.shields.io/badge/NGINX-1.22-blue)
- Php-fpm, tested with ![coverage](https://img.shields.io/badge/PHP-7.4.30-green)
- Php module mb_strings for the parsedown (apt install php-mbstring)


### Required Obsidian Options
- In the options `Files & Links` you need to set the `New link format` to `Relative path to file`
![Link Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/link.png "Link Options")

#### Graph
- For the Graph you need to install the plugin [Metadata Extractor](https://github.com/kometenstaub/metadata-extractor), you can also do this via the build in Community Plugin Browser
- Make sure to turn on the plugin
- In the settings, set the path of the metadata.json to your local vault root folder (like `C:\Users\John\MyNotes\metadata.json`, when your vault is MyNotes). if you transfer your vault later to a webserver, make sure the metadata.json will be transferred too.
- Also dont forget to set a timer how often the file should be written or just enable `Write JSON files automatically when Obsidian launches`

![Plugin Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/plugin_options.png "Plugin Options")

### Hide Folders and Files

- If you want to exclude specific folders, e.g. your attachment folder you can set the `HIDE_FOLDERS` variable or replace `$hideFolders = getenv('HIDE_FOLDERS');` in the helper.php with something like this `$hideFolders = 'attachments';`
- Folders and files starting with a "." (dot) are exclude by default
![Folders Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/folders.png "Folders Options")

### Advanced Options and Infos

#### Header, Logo and Start Page
you can adjust them via the index.php

- The About opens a .md file in a modal window (.about.md per default), you can change this in the helper.php `$about` variable
- The Logo, Blog and so on are nested in the index.php (line 47 e.g. for the link to the Blog)
- The Startpage is set to README.md, you can change this in the helper.php `$index` variable and in the perlite.js `homeFile` variable

#### Graph
The Graph is implemented with vis.js, so there are many options you can play on with, you can adjust them via the `options` object in the perlite.js

#### Root Dir
- Without setting a `NOTES_PATH` enviroment variable, Perlite takes the `perlite` directory as root folder, this means, you need to copy your content of the vault folder into the `perlite` directory (including metadata.json if you want a graph).
- If you want to change or specify the root directory of your vault, you can do this by changing the variable `$rootDir` in the helper.php or by setting the `NOTES_PATH` as php variable.
- You can see an example of this in the Release, just uncomment the `//$rootDir = 'Demo';` in the helper.php and see how the build changes, also notice the two metadata.json, one in the perlite folder and one in the Demo folder, depending on your rootDir either the one in perlite or the one in Demo will be used.

#### External Obsidian Links
- Add the cookie `perlite_edit=1` to display an edit button next to your documents for your own session. The button opens the current document in your Obsidian vault. Make sure to set the `HttpOnly` attribute to false. Otherwise, client-side script access for the cookie is blocked.

#### Python Parser
- There is a pythonParser.py to genereate the vis.js JSON structure out of the metadata.json. This script is not beeing used anymore but I will let it here.


### Dependencies (all included)

- [![coverage](https://img.shields.io/badge/Parsedown-1.7.4-lightgrey)](https://github.com/erusev/parsedown)
- [![coverage](https://img.shields.io/badge/jQuery-3.6.0-lightblue)](https://jquery.com/)
- [![coverage](https://img.shields.io/badge/Bootstrap-5.1.3-blue)](https://getbootstrap.com/)
- [![coverage](https://img.shields.io/badge/Highlight.js-11.4.0-green)](https://highlightjs.org/)
- [![coverage](https://img.shields.io/badge/vis.js-9.1.2-yellow)](https://visjs.org/)
- [![coverage](https://img.shields.io/badge/KaTeX.js-0.15.2-red)](https://katex.org/)
- [![coverage](https://img.shields.io/badge/Mermaid.js-9.1.2-orange)](https://mermaid-js.github.io/mermaid/)



## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but I would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be rendered by browser
- You should prevent that the metadata.json file is direct accessible via the browser (only the php engine need access to it). The extracted metadata.json contains the whole obsidian structure, so this file could be sensitive if you plan to exclude some files or folders from Perlite. However, the parsing is done by the php engine and it checks for every path if the file really exists in the provided vault, so files you excluded from the vault will also not be visible in the graph, but they are still present in the metadata.json. This is why you should prevent access to it.


## Why Perlite?
[Wiki](https://en.wikipedia.org/wiki/Perlite):
*Perlite is an amorphous volcanic glass ... typically formed by the hydration of obsidian.*
