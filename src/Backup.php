<?php

namespace IsaEken\LaravelBackup;

use Illuminate\Support\Str;
use IsaEken\LaravelBackup\Contracts\Backup\Manager;
use IsaEken\LaravelBackup\Contracts\Backup\Service;
use IsaEken\LaravelBackup\Contracts\HasBackupServices;
use IsaEken\LaravelBackup\Contracts\HasBackupStorages;
use IsaEken\LaravelBackup\Contracts\HasLogger;
use IsaEken\LaravelBackup\Contracts\HasNotifications;
use IsaEken\LaravelBackup\Contracts\HasPassword;
use IsaEken\LaravelBackup\Contracts\Runnable;

class Backup implements Manager, HasLogger, HasBackupServices, HasBackupStorages, HasPassword, HasNotifications
{
    use Traits\HasBackupServices;
    use Traits\HasBackupStorages;
    use Traits\HasPassword;
    use Traits\HasLogger;
    use Traits\HasNotifications;

    /**
     * Make file name using pattern.
     *
     * @param  string  $filename  Real file name with extension.
     * @param  Service  $service
     * @return string
     */
    protected function makeFilename(string $filename, Service $service): string
    {
        $pattern = config('backup.pattern', 'backup_:app_name_:service_name_:datetime.:extension');
        $extension = Str::afterLast($filename, '.');

        $replaces = [
            'filename' => $filename,
            'extension' => $extension,
            'app_name' => config('app.name', 'Laravel'),
            'service_name' => $service->getName(),
            'date' => date('Y-m-d'),
            'time' => date('H-i-s'),
            'datetime' => date('Y-m-d-H-i-s'),
        ];

        $filename = Str::of($pattern);
        foreach ($replaces as $key => $value) {
            $filename = $filename->replace(":$key", $value);
        }

        return $filename->value();
    }

    /**
     * Run backup services.
     *
     * @return array<Service>
     */
    protected function createBackups(): array
    {
        $backupServices = [];

        foreach ($this->getBackupServices() as $backupService) {
            $this->debug(trans('Running backup service: :service', [
                'service' => $backupService->getName(),
            ]));

            if ($backupService instanceof HasLogger && $this->getOutput() !== null) {
                $this->debug(trans('Setting logger ":logger" for service: :service', [
                    'logger' => $this->getOutput()::class,
                    'service' => $backupService->getName(),
                ]));

                $backupService->setOutput($this->getOutput());
            }

            if ($backupService instanceof HasPassword) {
                $this->debug(trans('Setting password "******" for service: service', [
                    'service' => $backupService->getName(),
                ]));

                $backupService->setPassword($this->getPassword());
            }

            $this->debug(trans('Backup generating...'));

            if ($backupService instanceof Runnable || method_exists($backupService, 'run')) {
                $backupService->run();
            } elseif (method_exists($backupService, '__invoke')) {
                $backupService->__invoke();
            } elseif (method_exists($backupService, '__call')) {
                $backupService->__call();
            }

            if ($backupService->isSuccessful()) {
                $this->debug(trans('Backup generated.'));

                if ($backupService->getOutputFile() !== null) {
                    $this->debug(trans('Output file: :file', [
                        'file' => $backupService->getOutputFile(),
                    ]));

                    $backupServices[] = $backupService;
                }
            } else {
                $this->error(trans('Backup is cannot be created!'));
            }
        }

        return $backupServices;
    }

    /**
     * Store backup service data files to storages.
     *
     * @param  array<Service>  $backupServices
     */
    protected function storeBackups(array $backupServices)
    {
        foreach ($backupServices as $backupService) {
            foreach ($this->getBackupStorages() as $driver => $storage) {
                $filename = $this->makeFilename(basename($backupService->getOutputFile()), $backupService);

                $this->debug(trans('Saving backup ":service" to ":filename" with using driver: :driver', [
                    'service' => $backupService->getName(),
                    'filename' => $filename,
                    'driver' => $driver,
                ]));

                if ($storage->put($filename, file_get_contents($backupService->getOutputFile()))) {
                    $model = config('backup.model', Models\Backup::class)();
                    $model->fill([
                        'filesystem' => $storage,
                        'driver' => $driver,
                        'filename' => $filename,
                        'size' => $storage->size($filename),
                        'date' => now(),
                    ]);
                    $model->save();
                    $this->debug(trans('Backup saved successfully.'));
                } else {
                    $this->error(trans(
                        'Backup ":service" cannot be saved to ":filename" with using driver: ":driver"', [
                            'service' => $backupService->getName(),
                            'filename' => $filename,
                            'driver' => $driver,
                        ]
                    ));
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $this->info(trans('Backup is started...'));
        $backupServices = $this->createBackups();

        $this->debug(trans('Saving backups to storages...'));
        $this->storeBackups($backupServices);

        $this->success(trans('Backup completed.'));
    }
}
