# Postcards Application (WIP)

## Installation 

* Create a new `.env` file by copying the example one. `cp .env.example .env`
* Add a new `app key` through `php artisan key:generate
* Set up a database and add credentials to the `.env` file`
* Run `php artisan migrate --seed` to migrate database tables and add admin users
* You can now already login through the `/login` page with the credentials from the `UserTableSeeder`.

## Create Postcard PDFs For The Front

Since we often need the same PDFs for the front of postcards, we can generate them easily through an artisan command.

```shell
php artisan postcards:generate-front-pdf postcards-front-climate-strike
```

This command will look for the image at `.../public/images/postcards-front-climate-strike.png` and if given, will create a postcard front PDF from it.
The new pdf can now be found at `.../public/pdfs/`.

Currently, only `pngs` are supported, but we can adapt that easily.
