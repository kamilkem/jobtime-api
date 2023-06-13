<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Model;

use Carbon\CarbonInterface;

interface CreatedAtInterface
{
    public function getCreatedAt(): ?CarbonInterface;

    public function setCreatedAt(CarbonInterface $createdAt): void;
}
