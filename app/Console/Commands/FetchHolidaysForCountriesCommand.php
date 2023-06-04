<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchHolidaysForCountriesCommand extends Command
{
    protected $signature = 'fetch:holidays {countryCode} {languageCode}';

    protected $description = 'Fetch public holidays for a country and display the dates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $countryCode = $this->argument('countryCode');
        $languageCode = $this->argument('languageCode');

        // might be changed according config's values
        $validFrom = now()->toDateString();
        $validTo = now()->addYear()->toDateString();

        $url = "https://openholidaysapi.org/PublicHolidays?countryIsoCode=$countryCode&languageIsoCode=$languageCode&validFrom=$validFrom&validTo=$validTo";

        $response = file_get_contents($url);
        $holidays = json_decode($response, true);

        dd($holidays);
    }
}
