# Browser Settings

Every visitor can adjust Perlite to their liking. These settings are stored in the browser (`localStorage`), so they are per visitor and per browser and don't change anything on the server. Clearing the browser data resets them to the defaults from the [.env Settings](03-Env-Settings.md).

Open the settings with the **gear icon** in the left ribbon. Some settings need a page reload to take effect.


## Appearance

| Setting | Description |
|---------|-------------|
| **Theme** | Select one of the installed [themes](07-Themes.md). *Reset* switches back to the default theme from `appearance.json`. |
| **Dark mode** | Toggle between dark and light color scheme. |
| **Print colors** | Color scheme used when printing a note: *Light theme variant* (default), *As on screen* or *Black & white*. Only the current note is printed. |


## Sizes

| Setting | Description |
|---------|-------------|
| **Font size** | Font size in pixels for the reading view. Default from `FONT_SIZE`, the reset button restores it. |
| **Panel sizes** | Reset the width of the left and right panel. Panels can be resized by dragging their border, the width is remembered. |


## Mobile

| Setting | Description |
|---------|-------------|
| **Pop Up** | Open a preview popup when clicking an internal link (instead of navigating to it). |


## Advanced

| Setting | Description |
|---------|-------------|
| **Disable Pop Hovers** | Disable the link previews on hover. Default from `DISABLE_POP_HOVER`. |
| **Show inline title** | Show the file name as title at the top of each note. |
| **Collapse Metadata** | Collapse the frontmatter properties of a note by default. |


## Right Panel

The buttons at the top of the right panel switch between its views. The selection is remembered:

| View | Description |
|------|-------------|
| **Local graph** | Graph of the current note and its links. Default from `SHOW_LOCAL_GRAPH`. Only available if a `metadata.json` exists, see [Graph](06-Graph.md). |
| **Outline** | Table of contents of the current note. Default from `SHOW_TOC`. |
| **Tags** | Tags of the current note. |


## Graph Settings

The global and the local graph have a settings button (gear icon in the graph view). The settings apply to both graphs:

| Setting | Default | Description |
|---------|---------|-------------|
| **Orphans** | on | Show files that are not linked to any other file. Turn off to speed up large graphs. |
| **Tags** | on | Show tags as nodes. |
| **Node scaling** | off | Make the node size depend on its number of connections. |
| **Auto-Reload** | on | Reload the graph automatically when a setting is changed. |
| **Style** | Dynamic | Edge style: Dynamic, Continuous, Discrete, DiagonalCross, StraightCross, Horizontal, Vertical, CurvedCW, CurvedCCW, CubicBezier. |
| **Node size** | 12 | Size of the nodes. |
| **Link thickness** | 1 | Width of the edges. |
| **Link distance** | 150 | Length of the edges. |

The *Restore default settings* button resets all graph settings.

---
Back to [Index](00-Index.md)
