<?php

namespace app\enums;

enum NotifySenderTypesEnum: string
{
    case SmsPilot = 'smsPilot';
    case Whatsapp = 'whatsapp';
    case Telegram = 'telegram';
}
