<?php

namespace app\dto;

class NotificationDTO extends AbstractDTO
{
    public function __construct(
        protected string $body,
        protected ?string $subject
    )
    {}

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}