# Excel Product Viewer

This simple PHP application lets you upload an Excel file and visualize products in a responsive grid. It relies on [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) installed via Composer.

## Structure

```
/ (project root)
├── index.php      # Upload form
├── upload.php     # File handling and product display
├── style.css      # Minimal styles
├── uploads/       # Temporary upload folder
└── vendor/        # PhpSpreadsheet (run `composer install`)
```

## Usage

1. Install dependencies:
   ```bash
   composer require phpoffice/phpspreadsheet
   ```
2. Place the project files on a PHP-enabled server.
3. Access `index.php` in your browser and upload an Excel file specifying the column letters for your data.

4. Ensure the `uploads/` directory is writable by the web server.
5. The script relies on `vendor/autoload.php`; run `composer install` if it is missing.
The uploaded file is stored temporarily in `uploads/` and deleted after parsing.
