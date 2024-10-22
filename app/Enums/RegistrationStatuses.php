<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RegistrationStatuses: string implements HasColor, HasIcon, HasLabel
{
    case PAID = 'paid';
    case PENDING = 'pending';
    case UNPAID = 'unpaid';

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'info',
            self::PAID => 'success',
            self::UNPAID => 'danger'
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::UNPAID => 'Unpaid',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::PAID => 'heroicon-o-check-badge',
            self::UNPAID => 'heroicon-o-x-circle',
        };
    }
	public static function toArray(): array
	{
		return array_map(fn($case) => $case->value, self::cases());
	}
}
