<?php

namespace App\Providers;

use App\Admin;
use App\Models\Admin\Reservation;
use App\Models\Admin\Store;
use Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class HtmlMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::macro('reservationStatus', function ($status) {
            $text = '';
            $type = '';
            switch ($status) {
                case Reservation::$STATUS_ATTENDED:
                    $text = __('messages.attended');
                    $type = 'status-attended';
                    break;
                case Reservation::$STATUS_NOT_ATTENDED:
                    $text = __('messages.not_attended');
                    $type = 'status-not-attended';
                    break;
                case Reservation::$STATUS_CANCELED:
                    $text = __('messages.canceled');
                    $type = 'status-canceled';
                    break;
                case Reservation::$STATUS_OPEN:
                    $text = __('messages.open');
                    $type = 'status-open';
                    break;
                case Reservation::$STATUS_MAXIMUM_CAPACITY:
                    $text = __('messages.maximum_capacity');
                    $type = 'status-pending';
                    break;
                case Reservation::$STATUS_NOT_STATUS:
                    $text = '';
                    $type = 'default';
                    break;
            }

            return "<span class=\"label label-default label-$type\">$text</span>";
        });
        Form::macro('logo', function () {
            $user = Auth::user();
            $brand = null;
            if ($user && $user->role == Admin::$ROLE_STORE && $user->store) {
                $brand = $user->store->brand;
            } elseif ($user && $user->role == Admin::$ROLE_BRAND) {
                $brand = $user->brand;
            }
            $src = $brand && $brand->logo ? '/' . ltrim($brand->logo, '/') : '/img/komenza/a-icono-app.svg';
            $title = $brand ? $brand->name : 'Komenza';
            return new HtmlString('<img class="img-circle komenza-brand-avatar" src="' . e($src) . '" title="' . e($title) . '" alt="' . e($title) . '" />');
        });
    }
}
