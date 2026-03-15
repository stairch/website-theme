# STAIR Theme

A custom WordPress theme for the STAIR student association at HSLU.

## Features

- **Dark Mode** - System preference detection with manual toggle, persisted in localStorage
- **Events Integration** - Displays upcoming events via The Events Calendar plugin
- **Contact Forms** - Integration with Contact Form 7
- **Responsive Design** - Mobile-first approach with Tailwind CSS
- **Auto Setup** - Automatically creates required pages and navigation menu on theme activation

## Tech Stack

- **Tailwind CSS v4** - Utility-first CSS framework
- **Lucide Icons** - Icon library (loaded via CDN)
- **TGM Plugin Activation** - Manages required plugin dependencies
- **Bun** - JavaScript runtime and package manager

## Requirements

- WordPress 6.8+
- PHP 8.0+
- [Bun](https://bun.sh/) or Node.js
- [Composer](https://getcomposer.org/)

### Required Plugins

The theme will prompt you to install these plugins on activation:

- [The Events Calendar](https://wordpress.org/plugins/the-events-calendar/) — Event management
- [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) — Contact forms
- [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) — Custom fields
- [FluentSMTP](https://wordpress.org/plugins/fluent-smtp/) — SMTP email delivery

## Installation

### Development Setup

First, create a shared directory into which this repo and [stairch/website-docker](https://github.com/stairch/website-docker) will be cloned

```bash
# Clone the repository
git clone https://github.com/stairch/website-theme.git
cd website-theme

# Install all dependencies (bun + composer)
bun run setup

# Build CSS
bun run build

# Or watch for changes during development
bun run dev
```

### Production

To deploy a new version of the theme, you need to create a tag following Semantic Versioning (e.g. `v1.1.0`).
After that the [production deployment workflow](./.github/workflows/prod.deploy.yml) starts and automatically deploys
it to the production server.

If you somehow need to deploy manually (e.g. fallback to an older version), follow these steps:

1. Copy `.env.example` to `.env` and change the values of the variables respectively. Make sure `REMOTE_PATH` is set to `~/public_html/wp-content/themes/stair-theme-<VERSION>`

1. Run `bun run setup` and `bun run build` to generate the compiled CSS
2. Run the `deploy.sh` script
3. Activate the theme in WordPress Admin → Appearance → Themes
4. Install the required plugins when prompted

## Available Scripts

| Command             | Description                               |
| ------------------- | ----------------------------------------- |
| `bun run setup`     | Install bun and Composer dependencies     |
| `bun run build`     | Build production CSS                      |
| `bun run dev`       | Watch mode — rebuilds CSS on file changes |
| `bun run build:css` | Build CSS only                            |
| `bun run watch:css` | Watch CSS only                            |

## Development Notes

### Adding New Required Plugins

Edit `inc/tgm-config.php` and add to the `$plugins` array:

```php
array(
    'name'     => 'Plugin Name',
    'slug'     => 'plugin-slug',
    'required' => true,  // or false for recommended
),
```

### Tailwind Configuration

Tailwind v4 configuration is in `input.css` using the new CSS-based config:

- Custom colors defined in `@theme` block
- Dark mode variant: `@custom-variant dark (&:where(.dark, .dark *))`
