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

use DigitalOceanV2\Entity\Key as KeyEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\HttpClient\Util\JsonObject;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Key extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return KeyEntity[]
     */
    public function getAll()
    {
        $keys = $this->httpClient->get(sprintf('%s/account/keys?per_page=%d', $this->endpoint, 200));

        $keys = JsonObject::decode($keys);

        $this->extractMeta($keys);

        return array_map(function ($key) {
            return new KeyEntity($key);
        }, $keys->ssh_keys);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function getById($id)
    {
        $key = $this->httpClient->get(sprintf('%s/account/keys/%d', $this->endpoint, $id));

        $key = JsonObject::decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $fingerprint
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function getByFingerprint($fingerprint)
    {
        $key = $this->httpClient->get(sprintf('%s/account/keys/%s', $this->endpoint, $fingerprint));

        $key = JsonObject::decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $name
     * @param string $publicKey
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function create($name, $publicKey)
    {
        $key = $this->httpClient->post(sprintf('%s/account/keys', $this->endpoint), ['name' => $name, 'public_key' => $publicKey]);

        $key = JsonObject::decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function update($id, $name)
    {
        $key = $this->httpClient->put(sprintf('%s/account/keys/%s', $this->endpoint, $id), ['name' => $name]);

        $key = JsonObject::decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function delete($id)
    {
        $this->httpClient->delete(sprintf('%s/account/keys/%s', $this->endpoint, $id));
    }
}
