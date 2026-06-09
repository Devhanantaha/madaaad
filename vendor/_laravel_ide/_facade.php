<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \Core\Models\User|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \Core\Models\User|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \Core\Models\User|null
     */
    public static function getUser();

    /**
     * @return \Core\Models\User
     */
    public static function authenticate();

    /**
     * @return \Core\Models\User|null
     */
    public static function user();

    /**
     * @return \Core\Models\User|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \Core\Models\User
     */
    public static function getLastAttempted();
}