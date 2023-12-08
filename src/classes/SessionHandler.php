<?php

namespace Models;

class SessionHandler implements ISessionHandler
{
    public function setSessionData(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getSessionData(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    public function regenerateSessionId(): void
    {
        session_regenerate_id();
    }
}
