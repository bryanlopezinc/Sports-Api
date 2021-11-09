<?php

declare(strict_types=1);

namespace Module\Football\Routes;

trait SerializeRoute
{
    public function jsonSerialize()
    {
        return (string) $this;
    }
}
