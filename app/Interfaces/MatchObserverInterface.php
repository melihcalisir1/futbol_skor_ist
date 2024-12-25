<?php

namespace App\Interfaces;

interface MatchObserverInterface
{
    /**
     * Maç güncellemelerini bildir
     *
     * @param array $matches
     * @return void
     */
    public function notify(array $matches): void;
}
