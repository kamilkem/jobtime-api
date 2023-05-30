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

namespace App\Dto;

use App\Entity\OrganizationUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrganizationUserInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(groups: [OrganizationUser::GROUP_WRITE])]
    public ?string $email = null;

    #[Groups(groups: [OrganizationUser::GROUP_WRITE])]
    public bool $owner = false;
}