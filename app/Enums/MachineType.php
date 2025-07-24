<?php

namespace App\Enums;

enum MachineType: string
{
    case PACKAGE_UNIT = 'Package Unit';
    case SPLIT = 'Split';
    case CASSETTE = 'Cassette';
    case CENTRAL_DUCTED = 'Central Ducted';
    case FLOOR_STANDING = 'Floor Standing';
    case CHILLER = 'Chiller';
    case WALL_MOUNTED = 'Wall Mounted';
    case PORTABLE = 'Portable';
    case WINDOW = 'Window';
    case VRF_VRV = 'VRF/VRV';
    case OTHER = 'Other';
}
