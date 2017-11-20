<?php

namespace App\Providers;

use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('not_same', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) != 1) {
                throw new InvalidArgumentException("Validation custom rule not_same requires at least 1 parameters.");
            }
            $other = Input::get($parameters[0]);

            return $value != $other;
        });

        Validator::replacer('not_same', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':other', $parameters[0], $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
    }
}
