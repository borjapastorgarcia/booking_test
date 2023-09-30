<?php

namespace App\Back\Shared\Domain\Bus\Query;


interface QueryBus
{
    public function ask(Query $query): Response|null;

}