<?php

namespace App\Collections;

use Override;
use InvalidArgumentException;
use App\DataTransferObjects\PemCertificate;
use Illuminate\Support\Collection;

/** @extends Collection<int,PemCertificate> */
final class PemCertificateCollection extends Collection
{
    #[Override]
    public function add($item): self
    {
        if (!$item instanceof PemCertificate) {
            throw new InvalidArgumentException('Item must be an instance of PemCertificate');
        }

        parent::add($item);

        return $this;
    }
}
