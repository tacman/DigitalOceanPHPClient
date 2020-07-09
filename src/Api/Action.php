<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\HttpClient\Util\JsonObject;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Action extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getAll()
    {
        $actions = $this->httpClient->get(sprintf('%s/actions?per_page=%d', $this->endpoint, 200));

        $actions = JsonObject::decode($actions);

        $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getById($id)
    {
        $action = $this->httpClient->get(sprintf('%s/actions/%d', $this->endpoint, $id));

        $action = JsonObject::decode($action);

        $this->meta = null;

        return new ActionEntity($action->action);
    }
}
