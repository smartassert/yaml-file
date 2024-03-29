<?php

declare(strict_types=1);

namespace SmartAssert\YamlFile\Validation;

class Validation implements ValidationInterface
{
    public function __construct(
        private readonly bool $isValid,
        private readonly ?ContextInterface $context,
        private readonly ?string $errorMessage,
        private readonly ?ValidationInterface $previous,
    ) {
    }

    public static function createValid(): ValidationInterface
    {
        return new Validation(true, null, null, null);
    }

    public static function createInvalid(
        ?ContextInterface $context,
        ?string $errorMessage = null,
        ?ValidationInterface $previous = null
    ): ValidationInterface {
        return new Validation(false, $context, $errorMessage, $previous);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    public function getErrorMessage(): ?string
    {
        $message = $this->errorMessage;
        $previous = $this->previous;

        while (null === $message && $previous instanceof ValidationInterface) {
            $message = $previous->getErrorMessage();
            $previous = $previous->getPrevious();
        }

        return $message;
    }

    public function getPrevious(): ?ValidationInterface
    {
        return $this->previous;
    }
}
