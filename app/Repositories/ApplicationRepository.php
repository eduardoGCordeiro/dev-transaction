<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Application;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Application\ApplicationRepositoryException;

class ApplicationRepository
{
    private $data;

    public function handle(object $data): Application
    {
        try {
            $this->data = (object) $data;
            return $this->saveApplication();
        } catch (ApplicationException $exception) {
            throw new ApplicationRepositoryException($exception->getMessage(), 422);
        }
    }

    private function saveApplication(): Application
    {
        return DB::transaction(function () {
            $application = new Application();
            $application->name = $this->data->name;
            $application->password = Hash::make($this->data->password);
            $application->save();

            return $application;
        });
    }
}
