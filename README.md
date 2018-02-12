#DBO DEV TEST
Basic Drupal 8 module using Atlas API to display a list of accommodation in NSW by region.

## Dependencies
None. Guzzle is used, which is part of core.
No custom templating was done, the results are being displayed in a form markup field. For best results, you should disable caching on your dev site to see page changes:

[Disable Drupal 8 caching during development](https://www.drupal.org/node/2598914)

## Files
* `dbo_dev.module` - Main module file which contains a form alter, which is passed off to a service `src/alter/DboDevFormAlter.php`
* `dbo_dev.services.yml` - Services file
* `src/Alter/DboFormAlterInterface.php` - Template form alter method which is implemented in `src/alter/DboDevFormAlter.php`
* `src/Form/DboDevForm.php` - Where the actual form is built. This is setup in the routing file `dbo_dev.routing.yml`
* `config/install/dbo_dev.config.yml` - Config file containing the base API URL and key. This is used in the `buildUrl` method in `src/Helper/DboHelper.php`
* `src/alter/DboDevFormAlter.php` - Form alter method which overrides the built form, calls the API and adds results to a markup field at the bottom of the form.
* `src/Helper/DboHelper.php` - Helper file. Contains an API call method, and a build URL method.

## Run on your dev site
* Install module.
* go to `[your url]/application/dbodevform`

