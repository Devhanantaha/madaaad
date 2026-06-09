<?php

namespace Illuminate\Contracts\Auth;

interface Guard
{
    /**
     * @return \Core\Models\User|null
     */
    public function user();
}