<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_title', 'UTA Registration');
        $this->migrator->add('general.site_tagline', 'Online Registration Portal');
        $this->migrator->add('general.contest_name', 'Unlock The Algorithm');
        $this->migrator->add('general.semester', 'Fall 24');
        $this->migrator->add('general.registration_deadline', now()->addDays(10));
        $this->migrator->add('general.registration_fee', 510);
        $this->migrator->add('general.preliminary_date', now()->addDays(20));
        $this->migrator->add('general.final_date', now()->addDays(30));
        $this->migrator->add('general.support_email', 'example@example.com');
        $this->migrator->add('general.support_phone', '');

        $this->migrator->add('general.countdown_text', "Time Before Registration Ends");
        $this->migrator->add('general.countdown_time', now()->addDays(30));


        $this->migrator->add('registration_form.lab_teacher_names', []);
        $this->migrator->add('registration_form.lab_teacher_names_enabled', true);

        $this->migrator->add('registration_form.departments', []);
        $this->migrator->add('registration_form.sections', []);

    }
};
