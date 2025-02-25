<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('keys_in_columns', function ($attribute, $value, $table, $validator) {
            if (!is_array($value)) {
                return false;
            }

            $columns = Schema::getColumnListing($table[0]);
            $extendedFilter = ['limit'];

            $columns = array_merge($columns, $extendedFilter);

            foreach (array_keys($value) as $key) {
                if (!in_array($key, $columns)) {
                    $validator->errors()->add('errors', 'Invalid filter '.$key.' selected');
                }
            }

            return true;
        });
    }
}
