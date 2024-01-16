<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    //admin path interface and repository
        $this->app->bind('App\Interfaces\admin\AccountInterface','App\Repository\admin\AccountRepository');
        $this->app->bind('App\Interfaces\admin\AllReportsAcceptInterface','App\Repository\admin\AllReportsAcceptRepository');
        $this->app->bind('App\Interfaces\admin\BelongBelongVulnerabilityInterface','App\Repository\admin\BelongBelongVulnerabilityRepository');
        $this->app->bind('App\Interfaces\admin\BelongVulnerabilityInterface','App\Repository\admin\BelongVulnerabilityRepository');
        $this->app->bind('App\Interfaces\admin\CorporateInterface','App\Repository\admin\CorporateRepository');
        $this->app->bind('App\Interfaces\admin\CorporateInvoiceInterface','App\Repository\admin\CorporateInvoiceRepository');
        $this->app->bind('App\Interfaces\admin\DashboardInterface','App\Repository\admin\DashboardRepository');
        $this->app->bind('App\Interfaces\admin\E_mailInterface','App\Repository\admin\E_mailRepository');
        $this->app->bind('App\Interfaces\admin\HobbyInterface','App\Repository\admin\HobbyRepository');
        $this->app->bind('App\Interfaces\admin\LeaderboardInterface','App\Repository\admin\LeaderboardRepository');
        $this->app->bind('App\Interfaces\admin\NotificationInterface','App\Repository\admin\NotificationRepository');
        $this->app->bind('App\Interfaces\admin\PaymentMethodInterface','App\Repository\admin\PaymentMethodRepository');
        $this->app->bind('App\Interfaces\admin\ProfileInterface','App\Repository\admin\ProfileRepository');
        $this->app->bind('App\Interfaces\admin\ProgramInterface','App\Repository\admin\ProgramRepository');
        $this->app->bind('App\Interfaces\admin\ReportChatInterface','App\Repository\admin\ReportChatRepository');
        $this->app->bind('App\Interfaces\admin\ReportInterface','App\Repository\admin\ReportRepository');
        $this->app->bind('App\Interfaces\admin\ReporterInterface','App\Repository\admin\ReporterRepository');
        $this->app->bind('App\Interfaces\admin\ReporterInvoiceInterface','App\Repository\admin\ReporterInvoiceRepository');
        $this->app->bind('App\Interfaces\admin\RewardInterface','App\Repository\admin\RewardRepository');
        $this->app->bind('App\Interfaces\admin\SearchJsonInterface','App\Repository\admin\SearchJsonRepository');
        $this->app->bind('App\Interfaces\admin\TypeTargetInterface','App\Repository\admin\TypeTargetRepository');
        $this->app->bind('App\Interfaces\admin\FaqInterface','App\Repository\admin\FaqRepository');
        $this->app->bind('App\Interfaces\admin\BlogInterface','App\Repository\admin\BlogRepository');
        $this->app->bind('App\Interfaces\admin\PlatformInterface','App\Repository\admin\PlatformRepository');
    
    
     
//reporter  path interface and repository
        $this->app->bind('App\Interfaces\reporter\AccountInterface','App\Repository\reporter\AccountRepository');
        $this->app->bind('App\Interfaces\reporter\AllReportsAcceptInterface','App\Repository\reporter\AllReportsAcceptRepository');
        $this->app->bind('App\Interfaces\reporter\ConnectionInterface','App\Repository\reporter\ConnectionRepository');
        $this->app->bind('App\Interfaces\reporter\DashboardInterface','App\Repository\reporter\DashboardRepository');
        $this->app->bind('App\Interfaces\reporter\GeneralInterface','App\Repository\reporter\GeneralRepository');
        $this->app->bind('App\Interfaces\reporter\HacktivityInterface','App\Repository\reporter\HacktivityRepository');
        $this->app->bind('App\Interfaces\reporter\InvoiceInterface','App\Repository\reporter\InvoiceRepository');
        $this->app->bind('App\Interfaces\reporter\LeaderboardInterface','App\Repository\reporter\LeaderboardRepository');
        $this->app->bind('App\Interfaces\reporter\NotificationInterface','App\Repository\reporter\NotificationRepository');
        $this->app->bind('App\Interfaces\reporter\PaymentMethodInterface','App\Repository\reporter\PaymentMethodRepository');
        $this->app->bind('App\Interfaces\reporter\ProfileInterface','App\Repository\reporter\ProfileRepository');
        $this->app->bind('App\Interfaces\reporter\ProgramInterface','App\Repository\reporter\ProgramRepository');
        $this->app->bind('App\Interfaces\reporter\ReportChatInterface','App\Repository\reporter\ReportChatRepository');
        $this->app->bind('App\Interfaces\reporter\ReportImageInterface','App\Repository\reporter\ReportImageRepository');
        $this->app->bind('App\Interfaces\reporter\ReportInterface','App\Repository\reporter\ReportRepository');
        $this->app->bind('App\Interfaces\reporter\RewardInterface','App\Repository\reporter\RewardRepository');
        $this->app->bind('App\Interfaces\reporter\SearchJsonInterface','App\Repository\reporter\SearchJsonRepository');
        $this->app->bind('App\Interfaces\reporter\SecurityInterface','App\Repository\reporter\SecurityRepository');
        $this->app->bind('App\Interfaces\reporter\VerificationInterface','App\Repository\reporter\VerificationRepository');

    


        
//corporate  path interface and repository
$this->app->bind('App\Interfaces\corporate\AccountInterface','App\Repository\corporate\AccountRepository');
$this->app->bind('App\Interfaces\corporate\AllReportsAcceptInterface','App\Repository\corporate\AllReportsAcceptRepository');
$this->app->bind('App\Interfaces\corporate\ConnectionInterface','App\Repository\corporate\ConnectionRepository');
$this->app->bind('App\Interfaces\corporate\DashboardInterface','App\Repository\corporate\DashboardRepository');
$this->app->bind('App\Interfaces\corporate\GeneralInterface','App\Repository\corporate\GeneralRepository');
$this->app->bind('App\Interfaces\corporate\InvoiceInterface','App\Repository\corporate\InvoiceRepository');
$this->app->bind('App\Interfaces\corporate\LeaderboardInterface','App\Repository\corporate\LeaderboardRepository');
$this->app->bind('App\Interfaces\corporate\NotificationInterface','App\Repository\corporate\NotificationRepository');
$this->app->bind('App\Interfaces\corporate\ProfileInterface','App\Repository\corporate\ProfileRepository');
$this->app->bind('App\Interfaces\corporate\ProgramInterface','App\Repository\corporate\ProgramRepository');
$this->app->bind('App\Interfaces\corporate\RewardInterface','App\Repository\corporate\RewardRepository');
$this->app->bind('App\Interfaces\corporate\SearchJsonInterface','App\Repository\corporate\SearchJsonRepository');
$this->app->bind('App\Interfaces\corporate\SecurityInterface','App\Repository\corporate\SecurityRepository');
$this->app->bind('App\Interfaces\corporate\VerificationInterface','App\Repository\corporate\VerificationRepository');
$this->app->bind('App\Interfaces\corporate\VisibilityProgramInterface','App\Repository\corporate\VisibilityProgramRepository');

    
    
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
