<?php

namespace App\Enums;

enum JsonCallType
{
    case PROPERTY;
    case METHOD;
    case METHOD_ON_PROPERTY;
    case IS_CLASS;
    case COLLECTION;
    case PROPERTY_CHAIN;
}
