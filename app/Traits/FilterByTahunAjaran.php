<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;

trait FilterByTahunAjaran
{
    /**
     * Scope a query to only include records of the active school year.
     */
    public function scopeTahunAjaranAktif(Builder $query)
    {
        // Skip if there's an explicit request filter for 'tahun_lulus'
        if (request() && request()->has('tahun_lulus')) {
            return $query;
        }

        $activeYear = Setting::getActiveTahunAjaran();
        if ($activeYear) {
            $column = defined('static::TAHUN_AJARAN_COLUMN') ? static::TAHUN_AJARAN_COLUMN : 'tahun_lulus';
            return $query->where($column, $activeYear);
        }

        return $query;
    }
}
