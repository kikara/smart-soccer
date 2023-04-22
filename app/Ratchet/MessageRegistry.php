<?php

namespace App\Ratchet;

/**
 * Реестр хранения данных для отправки
 */
class MessageRegistry
{
    private static MessageRegistry $instance;

    /**
     * Полезная информация
     * @var array
     */
    protected array $data = [];

    public static function getInstance(): MessageRegistry
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected function __construct()
    {
    }

    public function setPayload(array $data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
