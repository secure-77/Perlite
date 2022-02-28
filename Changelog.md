## 1.4.1 (planned)
- some code cleanup
- added seperate perlite.svg for the about header
- changed nginx docker config
- fixed multibyte utf basename to support chinese characters in filename and path


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
