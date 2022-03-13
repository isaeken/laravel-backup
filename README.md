# isaeken/laravel-backup

## Usage

> Work in progress do not use in production!

```shell
php artisan backup:run
php artisan backup:run --services=database,storage
php artisan backup:run --storages=local,s3,gcloud
php artisan backup:run --disable-notifications
php artisan backup:run --timeout=3
```

---

```shell
php artisan backup:list
```

```shell
output:
+---+--------------------------------------------+-------+---------------------+----------+
| # | Name                                       | Disk  |                Date |     Size |
+---+--------------------------------------------+-------+---------------------+----------+
| 1 | backup_2022-03-13-22-48-29.zip             | local | 2022-03-13 22:48:29 | 29.99 KB |
| 2 | backup_database_2022-03-13-22-48-29.sqlite | local | 2022-03-13 22:48:29 |    48 KB |
+---+--------------------------------------------+-------+---------------------+----------+
******************************************
*     Totally Used Storage: 77.99 KB     *
******************************************
```
