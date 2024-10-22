<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class RegistrationFormSettings extends Settings
{

    public array $lab_teacher_names;
    public bool $lab_teacher_names_enabled;

    public array $departments;
    public array $sections;

    public static function group(): string
    {
        return 'registration_form';
    }
}
