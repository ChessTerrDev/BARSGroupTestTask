<?php
declare(strict_types=1);

namespace BARSGroupTestTask\lib;

class Message
{
    private string $message;

    public function __construct(string $message)
    {
        $this->setMessage($message);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

}