<?php

declare(strict_types=1);

namespace Entense\Extractor;

use Entense\Extractor\Concerns\DataTransferTrait;
use Entense\Extractor\Contracts\DataTransfer as DataTransferContract;

abstract class DataTransfer implements DataTransferContract
{
    use DataTransferTrait;
}
