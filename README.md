# Excel Product Viewer


This simple PHP application lets you upload an Excel file and visualize products in a responsive grid. It relies on [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/). If Composer is unavailable you can set up the library manually (see below).

This simple PHP application lets you upload an Excel file (`.xls` or `.xlsx`) and visualize products in a responsive grid. It requires [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) installed via Composer in the `vendor/` directory.


## Structure

```
/ (project root)
├── index.php      # Upload form
├── upload.php     # File handling and product display
├── style.css      # Minimal styles
├── uploads/       # Temporary upload folder

└── vendor/        # PhpSpreadsheet library

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
   Alternatively download the [PhpSpreadsheet archive](https://github.com/PHPOffice/PhpSpreadsheet/releases) and extract it so that `vendor/autoload.php` exists.
   The uploaded file is stored temporarily in `uploads/` and deleted after parsing.

## Offline installation

If you cannot use Composer, download the latest `PhpSpreadsheet` release ZIP from
GitHub and unpack it. Copy the `vendor` folder from the archive into this project
so that `vendor/autoload.php` is available. `upload.php` will attempt to load this
file to register the library classes.

   ```bash
   composer require phpoffice/phpspreadsheet
   ```
2. Place the project files on a PHP-enabled server.
3. Access `index.php` in your browser and upload an Excel file specifying the column letters for your data.

The uploaded file is stored temporarily in `uploads/` and deleted after parsing.

