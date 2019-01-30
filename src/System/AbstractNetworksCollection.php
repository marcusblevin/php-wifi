<?php

declare(strict_types=1);

namespace Sanchescom\Wifi\System;

use Closure;
use Exception;
use pastuhov\Command\Command;

/**
 * Class AbstractNetworksCollection
 * @package Sanchescom\Wifi\System
 */
abstract class AbstractNetworksCollection
{
    /**
     * @var AbstractNetwork[]
     */
    protected $networks;

    /**
     * @param string $output
     * @return array
     */
    abstract protected function extractingNetworks(string $output): array;

    /**
     * @return string
     */
    abstract protected function getNetwork():? string;

    /**
     * @return string
     */
    abstract protected function getCommand(): string;

    /**
     * @return AbstractNetworksCollection
     * @throws Exception
     */
    public function scan(): AbstractNetworksCollection
    {
        $output = (string)Command::exec($this->getCommand());

        return $this->setNetworks(
            $this->extractingNetworks($output)
        );
    }

    /**
     * @return AbstractNetwork[]
     */
    public function getAll(): array
    {
        return $this->networks;
    }

    /**
     * @return AbstractNetwork[]
     */
    public function getConnected(): array
    {
        return $this->getFiltered(function (AbstractNetwork $network) {
            if ($network->connected) {
                return $network;
            }
        });
    }

    /**
     * @param string $ssid
     * @return AbstractNetwork[]
     */
    public function getBySsid(string $ssid): array
    {
        return $this->getFiltered(function (AbstractNetwork $network) use ($ssid) {
            if ($network->ssid == $ssid) {
                return $network;
            }
        });
    }

    /**
     * @param string $bssid
     * @return AbstractNetwork[]
     */
    public function getByBssid(string $bssid): array
    {
        return $this->getFiltered(function (AbstractNetwork $network) use ($bssid) {
            if ($network->bssid == $bssid) {
                return $network;
            }
        });
    }

    /**
     * @param array $networks
     * @return $this
     */
    protected function setNetworks(array $networks): self
    {
        $this->networks = array_map(function ($network) {
            /** @var AbstractNetwork $networkInstance */
            $networkInstance = $this->getNetwork();
            return $networkInstance::createFromArray($network);
        }, $networks);

        return $this;
    }

    /**
     * @param Closure $closure
     * @return AbstractNetwork[]
     */
    protected function getFiltered(Closure $closure): array
    {
        return array_values(array_filter($this->getAll(), $closure));
    }
}