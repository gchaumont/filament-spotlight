<?php

namespace pxlrbt\FilamentSpotlight;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use pxlrbt\FilamentSpotlight\Actions\RegisterPanels;
use pxlrbt\FilamentSpotlight\Actions\RegisterPages;
use pxlrbt\FilamentSpotlight\Actions\RegisterResources;
use pxlrbt\FilamentSpotlight\Actions\RegisterUserMenu;


class SpotlightServiceProvider extends ServiceProvider
{
    public function boot()
    {
        config()->set('livewire-ui-spotlight.commands', []);

        FilamentAsset::register([
            Css::make('spotlight-css', __DIR__.'/../resources/dist/css/spotlight.css'),
            Js::make('spotlight-js', __DIR__.'/../resources/dist/js/spotlight.js'),
        ], package: 'pxlrbt/filament-spotlight');


         Filament::serving(function () {
            $panel = filament()->getCurrentPanel();
           config()->set('livewire-ui-spotlight.include_js', false);
            if (Filament::hasTenancy()) {
                Event::listen(TenantSet::class, function () use ($panel) {
                    self::registerNavigation($panel);
                });
            } else {
                self::registerNavigation($panel);
            }
        });
    }

    public static function registerNavigation($panel)
     {
        RegisterPanels::boot($panel);
        RegisterPages::boot($panel);
        RegisterResources::boot($panel);
        RegisterUserMenu::boot($panel);
     }
}
