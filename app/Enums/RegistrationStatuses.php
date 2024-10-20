<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RegistrationStatuses: string implements HasColor, HasIcon, HasLabel
{
    case PENDING_PAYMENT = 'pending-payment';
    case PAYMENT_VERIFIED = 'payment-verified';
    case REJECTED = 'rejected';

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'info',
            self::PAYMENT_VERIFIED => 'success',
            self::REJECTED => 'warning'
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Pending payment',
            self::PAYMENT_VERIFIED => 'Payment verified',
            self::REJECTED => 'Rejected',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'heroicon-o-clock',
            self::PAYMENT_VERIFIED => 'heroicon-o-check-badge',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
	public static function toArray(): array
	{
		return array_map(fn($case) => $case->value, self::cases());
	}
}
