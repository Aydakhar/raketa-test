<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CartManager extends ConnectorFacade
{
    public $logger;

    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return ?Cart
     */
    public function getCart()
    {
        try {
            if ($this->connector->has(session_id())) {
                return $this->connector->get(session_id());
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }
}
