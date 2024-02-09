<?php

namespace pxlrbt\FilamentSpotlight\Actions;

use Filament\Pages\Page;
use Filament\Panel;
use LivewireUI\Spotlight\Spotlight;
use pxlrbt\FilamentSpotlight\Commands\PageCommand;

class RegisterPanels
{
    public static function boot(Panel $panel)
    {
        $panels = app('filament')->getPanels();

        foreach ($panels as $panelInstance) {

            if ($panelInstance->getId() == $panel->getId()) {
                continue;
            }

            if (method_exists($panelInstance, 'shouldRegisterSpotlight') && $panelInstance::shouldRegisterSpotlight() === false) {
                continue;
            }
            
            $currentPanel = app('filament')->getCurrentPanel();
            app('filament')->setCurrentPanel($panelInstance);
            $url = $panelInstance->getHomeUrl() ?? $panelInstance->getUrl();
            app('filament')->setCurrentPanel($currentPanel);

            if (blank($url)) {
                continue;
            }

            $command = new PageCommand(
                name: $panelInstance->getLabel(),
                url: $url
            );

            Spotlight::$commands[$command->getId()] = $command;
        }
    }
}
