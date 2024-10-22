<?php

namespace App\Settings;

use DateTime;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast;

class SiteSettings extends Settings
{
    public string $site_title;
    public string $site_tagline;
    public string $contest_name;
    public string $semester;
    public int $registration_fee;
    public string $registration_deadline;
    public string $preliminary_date;
    public string $final_date;

    public string $support_email;
    public string $support_phone;

    public string $countdown_text;
    public string $countdown_time;




    public static function group(): string
    {
        return 'general';
    }

}
