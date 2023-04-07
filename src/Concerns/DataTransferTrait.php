<?php

declare(strict_types=1);

namespace Entense\Extractor\Concerns;

trait DataTransferTrait
{
    use AttributableData;
    use AppendableData;
    use TransformableData;
    use BaseDataTransfer;
}
