# Postcards Application (instructions in progress)

## Installation 

* Create a new `.env` file by copying the example one. `cp .env.example .env`
* Add a new `app key` through `php artisan key:generate
* Set up a database and add credentials to the `.env` file`
* Run `php artisan migrate --seed` to migrate database tables and add admin users
* You can now already login through the `/login` page with the credentials from the `UserTableSeeder`.

