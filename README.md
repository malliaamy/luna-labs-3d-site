# Luna Labs 3D website

Private developer backup of the custom WordPress/WooCommerce website at https://lunalabs3d.com/.

## Repository contents

- The complete custom `luna-labs` WordPress theme and its compiled 3D homepage assets.
- WooCommerce template overrides used by the theme.
- `wordpress-content/lunalabs3d.WordPress.2026-09-06.xml`: WordPress WXR export containing the current pages, posts, products, portfolio projects, menus, taxonomy, custom fields, and media attachment records.
- `wordpress-content/IMPORT.md`: restoration instructions and important limitations.

## Local setup

1. Install a fresh WordPress site with WooCommerce.
2. Copy this repository into `wp-content/themes/luna-labs` and activate the theme.
3. Install the plugins listed below.
4. In WordPress, open **Tools → Import → WordPress**, then import the XML file in `wordpress-content/`.
5. Enable **Download and import file attachments** during import while the live site is available.
6. Re-save **Settings → Permalinks** and assign the imported navigation menus if required.
7. Review WooCommerce pages, shipping, taxes, payment gateways, email and privacy settings manually. Production credentials are intentionally not stored here.

## Plugins observed on production

- WooCommerce
- Complianz GDPR
- Hostinger Reach
- Jetpack
- The SEO Framework
- WP Mail SMTP
- Wordfence / Wordfence Login Security
- LiteSpeed Cache

Plugin versions and premium packages should be obtained from the WordPress administrator or Hostinger account. Do not commit licensed packages or credentials without checking their redistribution terms.

## Deployment

This repository is a backup/source repository. Pushing a commit does **not** update the live Hostinger website. Deployment must be performed separately through Hostinger/SFTP, or through a future CI/CD workflow configured with protected repository secrets.

## Security

Never commit `wp-config.php`, database dumps containing user/order data, API keys, SMTP credentials, payment credentials, salts, access tokens, server backups, cache/session files, or logs. Use environment variables or the host's secret storage for production configuration.
