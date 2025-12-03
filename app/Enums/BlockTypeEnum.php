<?php

namespace App\Enums;

enum BlockTypeEnum: string
{
    case Text = 'text';
    case Image = 'image';
    case TextImageRight = 'text_image_right';
    case TextImageLeft = 'text_image_left';
    case Slider = 'slider';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
