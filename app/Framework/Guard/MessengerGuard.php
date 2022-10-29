<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 02.01.2021
 * Time: 12:44
 */

namespace App\Framework\Guard;

use App\Common\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class MessengerGuard implements Guard
{
    protected Request $request;

    protected UserProvider $provider;

    protected ?Authenticatable $user;

    /**
     * Create a new authentication guard.
     *
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->user = null;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user(): Authenticatable|User|null
    {
        return $this->user;
    }

    public function id(): ?string
    {
        return $this->user()->id;
    }

    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['id'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user !== null) {
            $this->setUser($user);

            return true;
        } else {
            return false;
        }
    }

    public function setUser(Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     */
    public function onceUsingId(string|int $id): ?Authenticatable
    {
        $user = $this->provider->retrieveById($id);

        if ($user !== null) {
            $this->setUser($user);

            return $user;
        }

        return null;
    }

    public function hasUser(): bool
    {
        return $this->user !== null;
    }
}
