# Postcards Application (WIP)

## Installation 

* Create a new `.env` file by copying the example one. `cp .env.example .env`
* Add a new `app key` through `php artisan key:generate
* Set up a database and add credentials to the `.env` file`
* Run `php artisan migrate --seed` to migrate database tables and add admin users
* You can now already login through the `/login` page with the credentials from the `UserTableSeeder`.

## Configuration

### Campaigns

The `postSendHook` method on a campaign will be called after it was sent. It can be used to e.g. delete campaigns files after the campaign has been sent.

```php
public function postSendHook(): void
{
    Storage::disk('campaigns')->deleteDirectory($this->getCampaignDirectoryName());
}
```

## Create Postcard PDFs For The Front

We created an artisan command to generate those PDFs. If the image file will be stored locally, put it in the `publich/images/` folder. Then you can generate the PDF like this:
```shell
php artisan postcards:generate-front-pdf postcards-front-climate-strike.png --campaign-name=my-campaign
```

This command will look for the image at `.../public/images/postcards-front-climate-strike.png` and if given, will create a postcard front PDF from it.
The new pdf can now be found at `.../public/pdfs/static/my-campaign`.

If the image is stored online, use the URL instead of the image name like:
```shell
php artisan postcards:generate-front-pdf https://link-to.my/image --campaign-name=my-campaign
```

The new pdf can now be found at `.../public/pdfs/static/my-campaign`.
