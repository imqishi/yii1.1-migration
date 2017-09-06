# yii1.1-migration
yii-migration is availabe after 1.1.6, this project is used for yii version below that.

## Usage

1. Copy MigrateCommand.php to protected/commands directory.

2. Copy migrations directory to protected/ directory. Hint: 2017-09-06\* files is used for tests, remember delete them in your project.

3. In your project root, there should be a cron.php file, you can make a link for it such as `ln -s /path/to/project/cron.php /path/to/project/artisan`

4. You can use following instructions now:

```bash
# create a migration
php artisan migrate make your-migration-name

# edit file created in migrations directory

# exec
php artisan migrate
# or
php artisan migrate run
```
