# confection-wp-plugin
Confection WP Plugin. Controls output here: https://confection.io/poc/alpha/


## shortcodes list

- Loading Confection: `[confection-plugin-scripts]`

- Sending Event: `[confection-event event="event-name" value="event-value"]`


## String translation

- Import client.js i18n variable new values into $confection_strings on shortcodes.php

- Execute WP-CLI from plugin folder `wp i18n make-pot . languages/confection.pot` or at docker:
    - Open command line for WP CLI
    - Enter plugin language folder: `cd wp-content/plugins/confection-cable-wordpress`
    - Type `wp i18n make-pot . languages/confection.pot`

- Re-sync PO files and translate, then compile to MO files


## Using Docker

This repo is ready to be used with Docker. To do so:

- Clone this repo into your machine
- Enter in the folder `/.docker`
- Run the command `docker-compose -p "confection-wp-plugin" build` and `docker-compose up -d`
- The system should be acessible at http://localhost:8100


## WordPress Deploy

- Update plugin version at confection-wp-plugin.php
- Update stable version with same tag at readme.txt
- Deploy the same tag at Github
- A Github Action will automatically deploy to WordPress Plugin Repo. The new version may take up to 6 hours to be distributed.