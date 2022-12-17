# Helpful Notes and Tips for Development

## Adding A Modal To A Page

Adding a modal to a page differs a little compared to adding a global modal to the Layout. [Github Example](https://github.com/MakingSpiderSense/mss-timeclock/commit/024443e9ee25bf0b67fcb6c6e2c7570b912f0f4d)

## Outputting Validation Errors in the Flash Message

For forms in modals, it's better to also output validation errors in the flash message. Otherwise, the user may miss the error when the modal closes. [Github Example](https://github.com/MakingSpiderSense/mss-timeclock/commit/15e82185f80b56a05f30eb9abfcc60e2b0d84f86)