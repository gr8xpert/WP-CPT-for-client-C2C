# C2C Properties — WordPress Plugin

A professional WordPress plugin for real estate property listings, designed for Coast 2 Coast (C2C) Properties. Features a customizable shortcode grid display, single property detail pages with lightbox gallery, and comprehensive property management through WordPress admin.

## Features

- **Custom Post Type** — Dedicated "Properties" section in WordPress admin
- **Meta Box Fields** — Property type, location, price, bedrooms, bathrooms, size, energy rating, and more
- **21 Predefined Locations** — Dropdown selection for Costa del Sol areas
- **Shortcode System** — Flexible `[c2c_properties]` shortcode with filtering and sorting
- **Responsive Grid** — 4-column desktop → 2-column tablet → 1-column mobile
- **Hero Banner** — Full-width featured image with title/price overlay
- **Lightbox Gallery** — Click-to-enlarge images with keyboard navigation
- **Book Viewing CTA** — Integrated booking section with C2C branding

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## Installation

### Method 1: Upload via WordPress Admin

1. Download the plugin as a ZIP file
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Click **Activate**
5. Go to **Settings > Permalinks** and click **Save Changes** (flushes rewrite rules)

### Method 2: Manual Installation

1. Copy the `c2c-properties` folder into `wp-content/plugins/`
2. Activate **C2C Properties** in WP Admin > Plugins
3. Go to **Settings > Permalinks** and click **Save Changes** (flushes rewrite rules)

## Adding Properties

1. Go to **Properties > Add New** in the admin sidebar (building icon)
2. Enter the property **title** (e.g. "Amaranta Living Casares Golf")
3. Write the property **description** in the main editor
4. Fill in the **Property Details** meta box fields:

| Field | Description |
|-------|-------------|
| Property Type | Apartment, Villa, Penthouse, Townhouse, Land, Commercial |
| Location | Dropdown of 21 locations (Bahia Dorada, Cancelada, Casares, etc.) |
| Size (m²) | Property size in square metres |
| Property ID | Reference number (e.g. R4752058) |
| Price (EUR) | Numeric price |
| Bedrooms | Number of bedrooms |
| Bathrooms | Number of bathrooms |
| Energy Rating | A through G, or Awaiting Information |
| Property Link | URL for "Click here for more information" button |
| Book Viewing Link | URL for "Book Viewing" button |
| Gallery Images | Multi-image upload for photo gallery |

5. Optionally set a **Featured Image** (used as hero banner). If not set, the first gallery image is used automatically.
6. Click **Publish**

## Shortcodes

### Basic Usage

```
[c2c_properties]
```

Displays all properties in a 4-column grid (default 12 per page).

### Attributes

| Attribute | Default | Description |
|-----------|---------|-------------|
| `limit` | 12 | Number of properties to show |
| `type` | (all) | Filter by property type |
| `location` | (all) | Filter by location |
| `orderby` | date | Sort order: `date`, `price`, `price_asc`, `price_desc`, or `title` |

### Examples

```
[c2c_properties limit="8"]
[c2c_properties type="Villa"]
[c2c_properties location="Estepona"]
[c2c_properties type="Apartment" location="Casares Playa" limit="6"]
[c2c_properties orderby="price_asc"]
[c2c_properties orderby="price_desc"]
[c2c_properties orderby="title" limit="20"]
```

### Available Property Types

- Apartment
- Villa
- Penthouse
- Townhouse
- Land
- Commercial

### Available Locations

| | | |
|---|---|---|
| Bahia Dorada | Cancelada | Casares |
| Casares Playa | Dona Julia | El Paraiso |
| Estepona | La Alcaidesa | La Duquesa |
| La Noria | Manilva | Pueblo Nuevo de Guadiaro |
| Punta Chullera | San Diego | San Enrique |
| San Luis de Sabinillas | San Martin de Tesorillo | San Roque |
| Sotogrande | Torreguadiaro | Valle Romano |

## Single Property Page

Each property is accessible at `/dev-property/{slug}/`. The single page includes:

- **Hero Banner** — Featured image (or first gallery image) with title + price overlay
- **Highlights Row** — Bedrooms, bathrooms, size, property type in 2-column grid
- **Photo Gallery** — Thumbnail grid with lightbox (click to enlarge, arrow keys to navigate)
- **Property Details Table** — All meta fields displayed
- **Description** — Content from the WordPress editor
- **"More Information" Button** — Links to external property page (if Property Link is set)
- **"Book Viewing" Section** — CTA with C2C logo (if Book Viewing Link is set)

## Theme Override

To customize the single property template, copy `templates/single-property.php` from the plugin into your theme directory and rename it to `single-c2c_property.php`.

## File Structure

```
c2c-properties/
├── c2c-properties.php              # Main plugin file (registers CPT, hooks)
├── README.md                       # Documentation
├── includes/
│   ├── class-c2c-meta-box.php      # Admin meta box fields
│   ├── class-c2c-shortcode.php     # [c2c_properties] shortcode
│   └── class-c2c-template-loader.php # Single template filter
├── templates/
│   └── single-property.php         # Single property page template
└── assets/
    └── css/
        └── c2c-properties.css      # All frontend styles
```

## Changelog

### 1.3.0
- Added `price_asc` and `price_desc` sorting options
- Changed URL slug from `/property/` to `/dev-property/`
- Added tablet breakpoint for 2-column grid
- Updated mobile grid gap for consistency

### 1.2.0
- Redesigned single property page with hero banner
- Added 2-column highlights grid layout
- Added "Book Viewing" section with C2C branding
- Implemented lightbox gallery with keyboard navigation

### 1.1.0
- Converted location field to dropdown with 21 predefined locations
- Added server-side validation for location selection
- Changed location query from LIKE to exact match

### 1.0.0
- Initial release
- Custom post type for properties
- Meta box with property details
- Shortcode with filtering by type and location
- Responsive 4-column grid layout
- Single property template

## License

This plugin is proprietary software developed for C2C Properties.

## Author

Developed for **Coast 2 Coast Properties** (C2C)

---

For support or feature requests, please contact the development team.
