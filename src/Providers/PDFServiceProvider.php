<?php

namespace Mpdf\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use Mpdf\Mpdf;

class PDFServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = dirname(__FILE__,1) . '/config/pdf.php';
        $this->mergeConfigFrom($configPath, 'pdf');
        $this->publishes([
            $configPath
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('pdf', function ($cfg) {
            if (!empty($cfg)) {
                foreach ($cfg as $key => $value) {
                    Config::set('pdf.' . $key, $value);
                }
            }
            $mpdf = new Mpdf(
                Config::get('pdf')
            );
            $permissions = [];
            foreach (Config::get('pdf.protection.permissions') as $perm => $enable) {
                if ($enable) {
                    $permissions[] = $perm;
                }
            }
            $mpdf->SetProtection(
                $permissions,
                Config::get('pdf.protection.user_password'),
                Config::get('pdf.protection.owner_password'),
                Config::get('pdf.protection.length')
            );
            $mpdf->SetTitle(Config::get('pdf.title'));
            $mpdf->SetAuthor(Config::get('pdf.author'));
            $mpdf->SetWatermarkText(Config::get('pdf.watermark'));
            $mpdf->showWatermarkText = Config::get('pdf.showWatermark');
            $mpdf->watermark_font = Config::get('pdf.watermarkFont');
            $mpdf->watermarkTextAlpha = Config::get('pdf.watermarkTextAlpha');
            $mpdf->SetDisplayMode(Config::get('pdf.displayMode'));
            return new PDFWrapper($mpdf);
        });
    }
}
