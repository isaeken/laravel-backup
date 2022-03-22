<?php

namespace IsaEken\LaravelBackup\Console\Table;

use Symfony\Component\Console\Helper\TableStyle;

class RightAlignedCell extends TableStyle
{
    public function __construct()
    {
        $this->setPadType(STR_PAD_LEFT);
    }
}
