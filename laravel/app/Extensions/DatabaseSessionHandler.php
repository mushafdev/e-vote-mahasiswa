<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    protected function addUserInformation(&$payload)
    {
        if ($this->container->bound(Factory::class)) {
            // $payload['user_id'] = $this->webUserId();
            $payload['pemilih_id'] = $this->pemilihId();
        }

        return $this;
    }

    protected function webUserId()
    {
        $user = $this->getUser('web');
        
        return $user ? $user->id : null;
    }

    protected function pemilihId()
    {
        $user = $this->getUser('pemilih');
        
        return $user ? $user->id : null;
    }

    protected function getUser($guard)
    {
        return $this->container->make(Factory::class)->guard($guard)->user();
    }
}
