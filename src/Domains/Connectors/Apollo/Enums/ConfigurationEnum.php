<?php

declare(strict_types=1);

namespace Kanvas\Connectors\Apollo\Enums;

enum ConfigurationEnum: string
{
    case APOLLO_API_KEY = 'APOLLO_API_KEY';
    case APOLLO_JOB_SEGMENTS = 'APOLLO_JOB_SEGMENTS';
}
