<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;

use Filament\Support\Colors\Color;
use App\Http\Middleware\SetUserInactive;
use Filament\Navigation\NavigationGroup;
use App\Http\Middleware\TrackUserActivity;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use lockscreen\FilamentLockscreen\Lockscreen;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use TomatoPHP\FilamentAlerts\FilamentAlertsPlugin;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use lockscreen\FilamentLockscreen\Http\Middleware\LockerTimer;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName('Sultan Kudarat Social Services')
            ->brandLogo(asset('darklogo.png'))
            ->darkModeBrandLogo(asset('1.svg'))
            ->brandLogoHeight('3rem')
            
            ->favicon(asset('logo.png'))
            ->profile(isSimple: false)
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->sidebarCollapsibleOnDesktop()
           ->topNavigation()
            ->colors([
               'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Lime,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
             //   Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                LockerTimer::class,
               
                
            
            ])
            ->authMiddleware([
                Authenticate::class,
                Locker::class
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()->imageProvider(
                    MyImages::make()
                        ->directory('images/backgrounds')
                ),
                LightSwitchPlugin::make(),
                new Lockscreen(),
               SpotlightPlugin::make(),
                FilamentProgressbarPlugin::make()->color('#00FF00'),
                FilamentShieldPlugin::make(),
              
                
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Fuel Monitoring')
                     ->icon('heroicon-o-funnel'),
                    
                NavigationGroup::make()
                     ->label('Social Service')
                     ->icon('heroicon-o-user-group'),
                NavigationGroup::make()
                     ->label('Administration')
                     ->icon('heroicon-o-cog-8-tooth'),
                    ]);
    }
}
