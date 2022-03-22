<?php

namespace IsaEken\LaravelBackup\Console\Table;

enum TableStyle: string
{
    case DefaultStyle = 'default';
    case Borderless = 'borderless';
    case Compact = 'compact';
    case SymfonyStyleGuide = 'symfony-style-guide';
    case Box = 'box';
    case BoxDouble = 'box-double';
}
