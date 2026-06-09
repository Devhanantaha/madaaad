<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \Core\Models\User|null
     */
    public function user($guard = null);
}