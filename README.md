# UOM UI Kit

A Laravel package to help developers use Bootstrap via npm with Vite. It does not install Bootstrap via Composer; instead it provides an Artisan command to add Bootstrap to your project's `package.json` and scaffold imports.

## Install

```bash
composer require uom/ui-kit
```

## Usage

Run the installer to add npm dependencies and imports:

```bash
php artisan uom:install
# then
npm install
npm run build
```

### What it does

- Adds `bootstrap@^5.3.0` and `@popperjs/core@^2.11.8` to `dependencies` in `package.json` (use `--dev` to add to `devDependencies`).
- Appends `import 'bootstrap';` to `resources/js/app.js`.
- Adds either `@import "bootstrap/scss/bootstrap";` to `resources/sass/app.scss` if it exists, or `@import "bootstrap/dist/css/bootstrap.min.css";` to `resources/css/app.css`.

### Notes

- You can adjust imports per your project structure.
- After installing, manage Bootstrap entirely via npm.

## Commands

- **uom:install**: add Bootstrap to npm and scaffold Vite imports.
	- Flags: `--dev` to add to `devDependencies`.
- **uom:dashboard**: scaffold a Bootstrap dashboard page.
	- Flags: `--livewire` to create a Livewire component and wrapper page.
- **uom:page {path}**: scaffold a Bootstrap page at `resources/views/uom/{path}.blade.php`.
	- Flags: `-r` add a `Route::view('/{path}', 'uom.{path}')` route with name `uom.{path}`.
	- Flags: `-s` append a link to `resources/views/partials/uom-sidebar.blade.php`.
	- Flags: `-l` generate a Livewire component under `App/Http/Livewire/Uom` and embed it in the page.

### Examples

```bash
# Install Bootstrap via npm and scaffold imports
php artisan uom:install

# Generate a dashboard (Blade)
php artisan uom:dashboard

# Generate a dashboard as Livewire component
php artisan uom:dashboard --livewire

# Create a page with route + sidebar link
php artisan uom:page admin/users -r -s

# Create a Livewire page and route
php artisan uom:page reports/monthly -l -r
```
