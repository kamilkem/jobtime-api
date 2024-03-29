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

use Doctrine\Common\Collections\Collection;

interface ViewInterface extends UserResourceInterface, NameInterface, DescriptionInterface
{
    public const string GROUP_READ = 'view:read';
    public const string GROUP_WRITE = 'view:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public function getTeam(): TeamInterface;

    public function getDirectory(): DirectoryInterface;

    public function setDirectory(DirectoryInterface $directory, bool $updateRelation = true): void;

    /**
     * @return  Collection<TaskInterface>
     */
    public function getTasks(): Collection;

    public function addTask(TaskInterface $task, bool $updateRelation = true): void;

    public function removeTask(TaskInterface $task): void;
}
