# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


A web based markdown viewer optimized for [Obsidian](https://obsidian.md/) Notes

Just put your whole Obsidian vault or markdown folder/file structure in your web directory. The page builds itself. 

Its an open source alternative to  [obisidian publish](https://obsidian.md/publish).

Read more about Perlite and staging tips on my blog post: [Perlite on Secure77](https://secure77.de/perlite).
If you want to discuss about Perlite you can join the [Perlite Discord Server](https://discord.gg/pkJ347ssWT)


## Demo

[Perlite Demo](https://perlite.secure77.de/)


![Demo Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/screenshot.png "Demo Screenshot")

![Graph Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/graph.png "Graph Screenshot")

## Features

- Auto build up, based on your folder (vault) structure
- Support Obsidian Themes
- Full responsive
- No manual parsing or converting necessary
- Full interactive Graph
- LaTeX and Mermaid support
- Link to Obsidian Vault
- Search
- Support Obisdian tags, links, images and preview
- Dark and Light Mode



## Changelog
[Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)

### previous versions
- [Perlite 1.4.4 Demo](https://perlite.secure77.de/1.4.4)
- [Perlite 1.3 Demo](https://perlite.secure77.de/1.3)

## Wiki
Please check the [wiki](https://github.com/secure-77/Perlite/wiki)


## Install
Just put the content of the perlite directory in your web root directory, your notes should resident as a subfolder of perlite. You only need to set your root direcotry.

- For non Docker please check [Setup](https://github.com/secure-77/Perlite/wiki/01---Setup-(no-Docker))
- For Docker, please check [Docker Setup](https://github.com/secure-77/Perlite/wiki/02---Setup-Docker)


### Requirements
- Web server, tested with ![coverage](https://img.shields.io/badge/NGINX-1.22.1-blue)
- Php-fpm, tested with ![coverage](https://img.shields.io/badge/PHP-7.4.30-green) and ![coverage](https://img.shields.io/badge/PHP-8.1.11-green)
other webservers may work..

Please make sure you read also the [required settings](https://github.com/secure-77/Perlite/wiki/03---Perlite-Settings#required-settings)


## Themes
[Themes](https://github.com/secure-77/Perlite/wiki/Themes)

## Graph
[Graph Setup and Settings](https://github.com/secure-77/Perlite/wiki/Graph)

## Dependencies (all included)

- [![coverage](https://img.shields.io/badge/Parsedown-1.7.4-lightgrey)](https://github.com/erusev/parsedown)
- [![coverage](https://img.shields.io/badge/jQuery-3.6.1-lightblue)](https://jquery.com/)
- [![coverage](https://img.shields.io/badge/Bootstrap-5.1.3-blue)](https://getbootstrap.com/)
- [![coverage](https://img.shields.io/badge/Highlight.js-11.6.0-green)](https://highlightjs.org/)
- [![coverage](https://img.shields.io/badge/vis.js-9.1.2-yellow)](https://visjs.org/)
- [![coverage](https://img.shields.io/badge/KaTeX.js-0.16.4-red)](https://katex.org/)
- [![coverage](https://img.shields.io/badge/Mermaid.js-9.1.2-orange)](https://mermaid-js.github.io/mermaid/)


## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but I would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be rendered by browser
- You should prevent that the metadata.json file is direct accessible via the browser (only the php engine need access to it). The extracted metadata.json contains the whole obsidian structure, so this file could be sensitive if you plan to exclude some files or folders from Perlite. However, the parsing is done by the php engine and it checks for every path if the file really exists in the provided vault, so files you excluded from the vault will also not be visible in the graph, but they are still present in the metadata.json. This is why you should prevent access to it.


## Why Perlite?
[Wiki](https://en.wikipedia.org/wiki/Perlite):
*Perlite is an amorphous volcanic glass ... typically formed by the hydration of obsidian.*
