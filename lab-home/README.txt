Luna Labs 3D - exported project
================================

What's here:
- index.html        the page, with all script/style paths rewritten to load locally
- assets/            all JS module bundles + the CSS, beautified/formatted for readability
- images/            EMPTY - drop your 5 images in here (see below)

Missing images:
Chrome's "Save Page, Complete" saved these into a "..._files" folder on your machine
separately from the single HTML file you uploaded to me, so I don't have the actual
image bytes. Copy these 5 files from that "_files" folder into this "images" folder,
keeping the exact names:
  - luna-labs-mark.png
  - dragon-tower-01.jpg
  - shop-dragon.jpg
  - shop-black-gold.jpg
  - shop-chopper-stl.png

Once those are in place, open index.html directly in a browser (or serve the folder
with any static server, e.g. `npx serve .`) and it should render the same page,
fully local.

Important note on the JS:
This is the compiled/minified production bundle from the live site (framework code,
React-like runtime, the actual page logic in index-*.js, etc.) - beautified here so
it's readable, but it is NOT the original component source (no JSX/TS files, no
project structure). Treat it as a working reference/starting point rather than a
drop-in replacement for whatever ChatGPT originally generated.
