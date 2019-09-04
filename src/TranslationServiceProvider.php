<?php 

namespace Slavic\MissingPersons; 

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\ServiceProvider; 

class TranslationServiceProvider extends ServiceProvider
{ 
    /** 
     * The path to the current lang files. 
     * 
     * @var string 
     */ 
    protected $langPath; 

    /** 
     * Create a new service provider instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
        $this->langPath = resource_path('lang/' . App::getLocale());
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever('translations', function () {
            return collect(File::allFiles($this->langPath))->flatMap(function ($file) {
                return [
                    ($translation = $file->getBasename('.php')) => trans($translation),
                ];
            })->toJson(JSON_UNESCAPED_UNICODE);
        });
    }
}