<?php

/**
 * This file is part of the jobtime-backend package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Model;

use App\Entity\OrganizationUser;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends IdentifiableInterface, CreatedAtInterface, BaseUserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getRoles(): array;

    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): void;

    public function getBirthDate(): ?CarbonInterface;

    public function setBirthDate(?CarbonInterface $birthDate): void;

    public function isConfirmed(): bool;

    public function setConfirmed(bool $confirmed): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    /**
     * @return Collection<OrganizationUserInterface>
     */
    public function getOrganizationUsers(): Collection;

    public function addOrganizationUser(OrganizationUser $organizationUser): void;

    public function removeOrganizationUser(OrganizationUser $organizationUser): void;
}
