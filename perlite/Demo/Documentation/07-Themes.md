# Themes

Perlite supports Obsidian community themes. It loads all themes from the `.obsidian/themes` folder of your vault.


## Setup

1. Install the themes you want in Obsidian (**Settings → Appearance → Themes → Manage**).
2. Select your default theme in Obsidian. It is stored in `.obsidian/appearance.json`.
3. Publish the `.obsidian` folder together with your vault:

```
MyNotes/
└── .obsidian/
    ├── appearance.json        # default theme ("cssTheme")
    └── themes/
        ├── Minimal/
        │   └── theme.css
        └── Obsidian Nord/
            └── theme.css
```

Perlite uses the `cssTheme` from `appearance.json` as default. Without it, the built-in Perlite style is used.


## Web server access

The browser loads the `theme.css` files and `appearance.json` directly, so your web server must allow them, while the rest of `.obsidian` stays blocked. The provided nginx config ([`web/config/perlite.conf`](https://github.com/secure-77/Perlite/blob/main/web/config/perlite.conf)) already does this:

```nginx
location ~* ^/(.*)/.obsidian/appearance.json$ { allow all; }
location ~* ^/(.*)/.obsidian/(.*)/(.*).css$ { allow all; }
location ~* ^/(.*)/.obsidian/(.*)/(.*).map$ { allow all; }
location ~ \.(git|github|obsidian|trash) { deny all; }
```


## Switching themes

Visitors can switch between all installed themes and between dark and light mode in the [Browser Settings](04-Browser-Settings.md). Their choice is stored in the browser, *Reset* goes back to the default theme.

Some themes are built for features Perlite doesn't have (e.g. theme specific plugins / style settings), so not every theme looks exactly like in Obsidian.

---
Back to [Index](00-Index.md)
