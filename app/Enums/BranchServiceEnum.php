<?php

namespace App\Enums;

enum BranchServiceEnum: string
{
    case DOCTORS = 'doctors';
    case SERVICE = 'services';
    case BOTH = 'both'; // added
}
