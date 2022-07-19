<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AssignBadge::class,
        Commands\RefrshTokenGenerator::class,
        Commands\StravaLogAdd::class,
        Commands\MapMyRun::class,
        Commands\FitbitRefreshToken::class,
        Commands\CloseCompetitionAfterEndDate::class,
        Commands\RemoveUserBadgeeAfterLogRemove::class,
        //Commands\CloseChallengeAfterUserCompleteMiles::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    	//$schedule->command('Fitbit:Referesh:Token')->withoutOverlapping()->everyMinute();
        $schedule->command('Strava:Referesh:Token')->withoutOverlapping()->everyMinute();
        $schedule->command('MapMyRun:Accesstoken:By:Referesh:Token')->withoutOverlapping()->everyMinute();
        $schedule->command('Close:Competition:AfterEndDate')->withoutOverlapping()->everyFiveMinutes();
        $schedule->command('Remove:User:Badgee:AfterLogRemovee')->withoutOverlapping()->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
