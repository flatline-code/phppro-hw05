<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WorkWithFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file-manager {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command reads or writes a file if the user is over 18 years old.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $age = $this->ask("{$name}, how old are you?");

        if (is_numeric($age) === false) return;

        if ($this->isAdult($age) === false && $this->confirm('Are you sure you want to continue?') === false) {
            return;
        };

        $option = $this->anticipate('Please type an option, read or write?', ['read', 'write']);
        $pathToFile = $this->ask('Enter path to the file.');

        if ($option === 'read') {
            $this->readDataFromFile($pathToFile);
        }

        if ($option === 'write') {
            $this->writeDataToFile($pathToFile);
        }
    }

    private function readDataFromFile(string $pathToFile): void
    {
        if (file_exists($pathToFile)) {
            $contents = file_get_contents($pathToFile, FILE_USE_INCLUDE_PATH);
            $this->info($contents);
        }

        $this->error('File does not exist.');
    }

    private function writeDataToFile(string $pathToFile): void
    {
        $userData = [
            'name' => $this->argument('name'),
            'gender' => $this->ask('Please enter your gender.'),
            'city' => $this->ask('Please enter your city.'),
            'phone' => $this->ask('Please enter your phone number.'),
        ];

        $jsonData = json_encode($userData);

        if (file_put_contents($pathToFile, $jsonData)) {
            $this->info('Data successfully written to file.');
        }

        $this->error('Failed to write data to file.');
    }

    private function isAdult(int $age): bool {
        return $age > 18;
    }
}
