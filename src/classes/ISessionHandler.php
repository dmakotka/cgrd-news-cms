<?php

namespace Models;

interface ISessionHandler
{
    public function setSessionData(string $key, $value): void;
    public function getSessionData(string $key);
    public function isLoggedIn(): bool;
    public function regenerateSessionId(): void;
}
