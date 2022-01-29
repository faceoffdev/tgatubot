<?php
/*
 * @author    Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 09.07.2021
 * Time: 19:56
 */

namespace App\Bot\Decorators;

use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\Drivers\Telegram\TelegramDriver;

class ChatTelegramUser
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getChatId(): int
    {
        return (int) $this->user->getId();
    }

    public function getUsername(): ?string
    {
        return $this->user->getUsername();
    }

    public function getFullName(): string
    {
        return trim($this->user->getFirstName() . ' ' . $this->user->getLastName());
    }

    public function getMessenger(): string
    {
        return TelegramDriver::DRIVER_NAME;
    }
}
