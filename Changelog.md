## 1.5.9
- added YouTube link support #133 thanks to @rsubr
- added image postion support thanks to @ar0x4
- fixed direct page links [#90](https://github.com/secure-77/Perlite/issues/90)
- added support for webp images [#126](https://github.com/secure-77/Perlite/issues/126)
- fixed kartex problem PR [#131](https://github.com/secure-77/Perlite/pull/131) thanks to @Yaro2709
- fixed obsidian edit link thanks to klgzzz
- changed site title to use the env. variable site_title
- fixed issue [#128](https://github.com/secure-77/Perlite/issues/128) thanks to @rgaricano
- added m4a support thanks to @rgaricano
- added img link support to external urls issue [#89](https://github.com/secure-77/Perlite/issues/89)
- added tasks support, issue [#118](https://github.com/secure-77/Perlite/issues/118)
- updated mermaid to 11.2.1
- updated katex to 0.16.11
- updated highlight.js to 11.10.0
- changed base image for perlite_web to nginx:stable, issue [#100](https://github.com/secure-77/Perlite/issues/100)


## 1.5.8
- merged PR #111 thanks to @selfiens
- merged PR #112 thanks to @selfiens
- merged PR #106 thanks to @Shardbyte
- updated obsidian app.css
- fixed yaml frontmatter [#107](https://github.com/secure-77/Perlite/issues/107)
- added support for alternate image text [#92](https://github.com/secure-77/Perlite/issues/92)
- added support for collapsed callouts [#113](https://github.com/secure-77/Perlite/issues/113)
- added support for embedded pdf and videos [#105](https://github.com/secure-77/Perlite/issues/105)
- added random node function [#109](https://github.com/secure-77/Perlite/issues/109)
- added support for absolut pathes [#90](https://github.com/secure-77/Perlite/issues/90)
- updated Demo Documetns and Themes
- updated build.sh to force composer updates
- updated highlight.js to 11.9.0
- updated katex to 0.16.9
- updated vis-network to 9.1.9
- added new custom logo and social media section
- fixed issue Folder arrow marker randomly disappears [#94](https://github.com/secure-77/Perlite/issues/94)
- changed to show TOC and local Graph at the same time


## 1.5.7
- added social media meta tags and variables
- added github action for docker build process and arm support, issue #65
- improved loading performance, the graph linking will only be done at the first page load, issue #97
- provided a python script to perform as alternative for the link generation, issue #97
- included mermaid 10.3.0 via cdn as composer had issues to load it
- updated highlight.js to 11.8.0
- updated katex to 0.16.8
- updated jquery to 3.7.1
- fixed issue #74 (custom heading links)
- fixed heading references in links to other notes
- implemented copy code button issue #68
- added a little padding for the headers
- merged PR #102 (thx to @selfiens)
- seperated php composer into two files (npm-assets and php-assets)
- fixed issue #91


## 1.5.6
- fixed heading references (issue #74)
- added safemode environment variable (PR #88) thanks to @NotGovernor
- added default side panel size related of screen size (issue #85)


## 1.5.5
- updated some html structure to fit the latest app.css and theme styles
- updated app.css
- removed Bootstrap dependency
- fixed ampersand problem in global graph (issue #47)
- added default font size environment variable (issue #80)
- added escape key function for modal close button (PR #82) thank to @stellarix
- added homepage environment variable (PR #84) thanks to @NotGovernor
- added composer
- added build script
- updated highlight.js to 11.7.0
- updated jquery to 3.7.0
- updated mermaid to 8.12.1
- updated vis-network to 9.1.6


## 1.5.4
- fixed img max width
- fixed image modal (issue #58)
- fixed double links to nodes (issue #45) thanks to @catrone3
- added svg format, thanks to @lennartbrandin
- added variable for allowed file link types, thanks to @dbohn
- changed the gprah render solver from barnesHut to forceAtlas2Based to improve perfomance (issue #66)
- added graph loading text
- added option and default behavior to disable Pop Hovers
- added default behavior to show table of content instead of local graph


## 1.5.3
- support file links with exclamation mark and aliases (issue #55)
- support image resizing (issue #54)
- fixed $refName missing php variable (PR #56)


## 1.5.2
- fixed $closing variable error
- set focus to search field
- fixed #Header reference in internal links (Issue #32)
- fixed error with links to non existing files (Graph)
- fixed ampersand problem in internal links and graph (Issue #47)
- added mousehover for internal links, feature request #43 
- added popup feature for internal links on mobile (setting), feature request #43 
- fixed padding-right issue on mobile view
- moved some instructions to the wiki
- added light mode (in the settings)
- updated katex to 0.16.4
- fixed problem with two _ (underscorces) per line in katex context


## 1.5.1
- fixed tags containing a hyphen or slash (Issue #39)
- fixed docker-compose-dev.yml config
- fixed callouts and contiuned quotes (Issue #40 and #27)
- fixed logo position
- Demo Vault is default if no NOTES_PATH is defined
- fixed line breaks if LINE_BREAKS isn´t defined


## 1.5
- Complete redesign based on obsidian css
- support for Obsidian themes
- added tag search
- added metadata support
- added different settings for the graph styling
- added different settings for the viewer, like text size
- settings stored in local browser storage
- updated JQuery to version 3.6.1
- updated HighlightJS to version 11.6.0
- updated Docker image to use latest php:fpm-alpine version (8.1.11)
- updatet Nginx config to allow Themes
- added docker compose variable for markdown linebreaks


## 1.4.5 RC
- added "edit button", enable it via cookie, thx to @Tooa


## 1.4.4
- some code cleanup
- replaced mermaid.min.js with the correct one (29.1.)
- implemented start page (README.md) for non mobile view
- hide graph display, when node has no graph
- some visual graph updates
- changed order: folders are now always on top
- fixed sort order to be case insensitive
- adjusted the docker image and container naming
- updated the nginx version to 1.22 and php to 7.4.30 for docker
- adjusted blockquote style and added callouts support (thanks to @Tooa)
- fixed sidebar menu height
- fixed problem with direct links and `&` chars
- fixed a graph linking error when some files are missing from the metadata.json


## 1.4.3
- added mermaid 8.7.0 (mermaid.min.js)
- changed order for folders and files with underscores (they are now on top)
- added sec77/perlite:latest to the docker hub
- changed the docker compose files, the hub images is used now
- updated vis-js to version 9.1.2
- fixed problems with multiple links in one line


## 1.4.2
- enabled simple line break in parsedown
- fixed sidebar height
- fixed max img width & height
- removed # from title
- added Perlite Discord Server
- added support for custom internal Obsidian link names
- improved style of tables
- url update in browser


## 1.4.1
- some code cleanup
- added separate perlite.svg for the about header
- changed nginx docker config
- fixed multibyte utf basename to support chinese characters in file name and path
- fixed menu collapse problem when folder name contains dots or begins with a number
- fixed image size in mobile view
- fixed image line break issue
- fixed full graph view when current node has no links and no-link filter is enabled
- changed color of current node in the graph view
- LaTeX support 
    - added katex.min.js
    - added katex.min.css
    - added fonts
    - added auto-render.min.js
- fixed navigator when file or folder names are to long


## 1.4
- updated highlight.js to 11.4.0
- updated Bootstrap JS files to 5.1.3
- changed default (dark) theme from darkly to Bootswatch theme "slate"
- fixed direct links when Perlite is in a subfolder of the root direcotry
- removed the flatly theme, a11y-light theme and disabled the "light mode"
- many layout redesigns like
    - changed the header size
    - moved the searchbar to the navbar
    - added Nerd Fonts as default Font
    - added a right navbar for the graph
    - highlight current navbar entry
    - set focus on current navbar entry if called by a link
    - other small layout changes
- added separated scrollbars for navbar and content
- added rel="noopener noreferrer" attributes for external links
- added icon for external links
- added a visual graph via vis.js
    - metadata.json parsing (only adds nodes if they really exists in the folder structure)
    - show graph and direct links for every note in the right navbar
    - added modal for full graph view
    - added filter (hide nodes without link) in the full graph view (saved via cookie)
- improved the search
    - enabled case insensitive
    - included filename for the search
    - removed the "open recent search" (because its unnecessary)
- updated the Readme
- updated the Demo Documents


## 1.3
- added support for inplace links
- added support for PDF files / links
- changed in place image behaviour
- new logo
- added favicon
- adjust image view in about modal
- changed "About" behaviour
- added changelog
- outsourced docker instructions from readme
- changes link behaviour (added link get parameter to fix problems with unwanted queries)
- removed "not allowed" content
- added cookie for theme settings
- adjust mobile margins


## 1.2
- added Docker  
- added direct Links  
- changed base dir logic  
- update Readme


## 1.1
- added mobile responsive
- added search
- added theme support and darkmode 


## 1.0
- First prod. ready release
