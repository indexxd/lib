<?php

namespace App\Util;

interface EntitySluggerInterface {
    /**
     * Returns a unique slug for provided entity
     * @throws Exception if no unique slug was found
     */
    public function getUnique($entity, string $property): string;
}