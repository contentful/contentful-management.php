<?php
declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

abstract class AbstractCustomMessageValidation
{
    /**
     * @var string|null
     */
    private $message;

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function jsonSerialize(): array
    {
        return $this->message ? ['message' => $this->message] : [];
    }
}
