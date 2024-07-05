<?php

namespace App\Providers\Filament;

use App\Filament\Resources\PelangganResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Filament\Navigation\MenuItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use App\Filament\Resources\UserResource;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->authGuard('web')
            // ->profile()

            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-cog-6-tooth')
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:1024' //only accept jpeg and png files with a maximum size of 1MB
                    )
                    ->shouldShowDeleteAccountForm(false),
                    \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Edit Profil')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-cog-6-tooth')
            ])
            // ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            //     return $builder->groups([
            //         NavigationGroup::make()
            //             ->items([
            //                 NavigationItem::make('dashboard')
            //                     ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
            //                     ->icon('heroicon-o-home')
            //                     ->url(fn (): string => Dashboard::getUrl())
            //                 // ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard'))
            //                 ,
            //             ]),
            //         NavigationGroup::make('Data Pelanggan')
            //             ->items([
            //                 PelangganResource::getNavigationItems(),
            //             ]),

            //         NavigationGroup::make('User')
            //             ->items([
            //                 ...UserResource::getNavigationItems(),
            //                 NavigationItem::make('Roles')
            //                     ->icon('heroicon-o-user-group')
            //                     ->isActiveWhen(fn (): bool => request()->routeIs([
            //                         'filament.admin.resources.roles.index',
            //                         'filament.admin.resources.roles.create',
            //                         'filament.admin.resources.roles.view',
            //                         'filament.admin.resources.roles.edit'
            //                     ]))
            //                     ->hidden(fn (): bool => !auth()->user()->can('role-permission'))
            //                     ->url(fn (): string => '/admin/roles'),
            //                 NavigationItem::make('Permissions')
            //                     ->icon('heroicon-o-lock-closed')
            //                     ->isActiveWhen(fn (): bool => request()->routeIs([
            //                         'filament.admin.resources.permissions.index',
            //                         'filament.admin.resources.permissions.create',
            //                         'filament.admin.resources.permissions.view',
            //                         'filament.admin.resources.permissions.edit'
            //                     ]))
            //                     ->hidden(fn (): bool => !auth()->user()->can('role-permission'))
            //                     ->url(fn (): string => '/admin/permissions'),

            //             ]),
            //     ]);
            // })
            ;
    }
}
