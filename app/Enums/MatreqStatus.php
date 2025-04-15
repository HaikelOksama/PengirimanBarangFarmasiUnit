<?php

namespace App\Enums;

enum MatreqStatus : string
{
    case REQUEST = 'request';
    case KIRIM = 'kirim';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            MatreqStatus::REQUEST => 'Diminta',
            MatreqStatus::KIRIM => 'Kirim',
            MatreqStatus::SELESAI => 'Selesai',
        };
    }
    
}
