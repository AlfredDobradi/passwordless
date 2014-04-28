<?php namespace Adobradi\Passwordless;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Guard;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Hashing\BcryptHasher;

class PasswordlessServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('adobradi/passwordless','passwordless');
		
		\Auth::extend('passwordless',function() {
            \App::bind('UserProviderInterface','\Illuminate\Auth\EloquentUserProvider');
            \App::bind('HasherInterface','\Illuminate\Auth\BcryptHasher');

            $provider = function($model) {
                return new \Illuminate\Auth\EloquentUserProvider(\App::make('hash'),$model);
            };

            return new PasswordlessGuard($provider('User'),\App::make('session.store'));
        });
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
