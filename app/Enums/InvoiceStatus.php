<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Paid = 'paid';
    case Overdue = 'overdue';

    public static function options(): array
    {
        return [
            self::Draft->value => 'Draft',
            self::Sent->value => 'Sent',
            self::Paid->value => 'Paid',
            self::Overdue->value => 'Overdue',
        ];
    }

    public static function labels(): array
    {
        return [
            self::Draft->value => 'Draft',
            self::Sent->value => 'Sent',
            self::Paid->value => 'Paid',
            self::Overdue->value => 'Overdue',
        ];
    }
} 