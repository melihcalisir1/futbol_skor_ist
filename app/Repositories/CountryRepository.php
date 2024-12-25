<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Support\Collection;

class CountryRepository
{
    protected $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    /**
     * Tüm ülkeleri getir
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * API'den gelen ülkeleri kaydet
     *
     * @param array $countries
     * @return void
     */
    public function syncCountries(array $countries): void
    {
        foreach ($countries as $country) {
            $this->model->updateOrCreate(
                ['code' => $country['code']],
                [
                    'name' => $country['name'],
                    'flag' => $country['flag'] ?? null
                ]
            );
        }
    }

    /**
     * Ülke koduna göre ülke getir
     *
     * @param string $code
     * @return Country|null
     */
    public function findByCode(string $code): ?Country
    {
        return $this->model->where('code', $code)->first();
    }
}
