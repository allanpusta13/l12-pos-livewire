<?php

namespace App\Providers;

use Filament\Actions;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configuredFilament();
    }

    public function configuredFilament()
    {
        // pages
        Actions\CreateAction::configureUsing(function (Actions\CreateAction $action) {
            $action->icon(FilamentIcon::resolve('actions::create-action') ?? 'heroicon-m-plus')
                ->color('success');
        });

        Actions\ViewAction::configureUsing(function (Actions\ViewAction $action) {
            $action->icon(FilamentIcon::resolve('actions::view-action') ?? 'heroicon-m-eye')
                ->color('secondary');
        });

        Actions\EditAction::configureUsing(function (Actions\EditAction $action) {
            $action->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil')
                ->color('info');
        });

        Actions\DeleteAction::configureUsing(function (Actions\DeleteAction $action) {
            $action->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-trash');
        });

        Actions\RestoreAction::configureUsing(function (Actions\RestoreAction $action) {
            $action->icon(FilamentIcon::resolve('actions::restore-action.grouped') ?? 'heroicon-m-arrow-uturn-left');
        });

        Actions\ForceDeleteAction::configureUsing(function (Actions\ForceDeleteAction $action) {
            $action->icon(FilamentIcon::resolve('actions::force-delete-action.grouped') ?? 'heroicon-m-trash');
        });

        // tables
        Tables\Actions\CreateAction::configureUsing(function (Tables\Actions\CreateAction $action) {
            $action->icon(FilamentIcon::resolve('actions::create-action') ?? 'heroicon-m-plus')
                ->color('success');
        });

        Tables\Actions\ViewAction::configureUsing(function (Tables\Actions\ViewAction $action) {
            $action
                ->iconButton();
        });

        Tables\Actions\EditAction::configureUsing(function (Tables\Actions\EditAction $action) {
            $action
                ->color('info')
                ->iconButton();
        });

        Tables\Actions\DeleteAction::configureUsing(function (Tables\Actions\DeleteAction $action) {
            $action
                ->requiresConfirmation()
                ->iconButton();
        });

        Tables\Actions\RestoreAction::configureUsing(function (Tables\Actions\RestoreAction $action) {
            $action
                ->requiresConfirmation()
                ->iconButton();
        });

        Tables\Actions\ForceDeleteAction::configureUsing(function (Tables\Actions\ForceDeleteAction $action) {
            $action
                ->requiresConfirmation()
                ->iconButton();
        });

        // forms
        Forms\Components\DatePicker::configureUsing(function (Forms\Components\DatePicker $component) {
            $component
                ->suffixIcon('heroicon-m-calendar')
                ->native(false);
        });
    }
}
