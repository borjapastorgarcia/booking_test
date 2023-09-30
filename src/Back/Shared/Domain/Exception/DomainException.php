<?php

declare(strict_types=1);


namespace App\Back\Shared\Domain\Exception;


use Exception;
use Throwable;

class DomainException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message === "") {
            $message = 'Domain violation exception';
        }
        parent::__construct($message, $code, $previous);
    }
}