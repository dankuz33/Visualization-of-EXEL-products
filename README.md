# Excel Product Viewer

This simple PHP application lets you upload an Excel file (`.xls` or `.xlsx`) and visualize products in a responsive grid. It requires [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) installed via Composer in the `vendor/` directory.

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

The uploaded file is stored temporarily in `uploads/` and deleted after parsing.
