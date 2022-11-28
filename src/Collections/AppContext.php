<?php declare(strict_types=1);

namespace GraphQL\Collections;

use GraphQL\Collections\Data\Collection;

/**
 * Instance available in all GraphQL resolvers as 3rd argument.
 */
class AppContext
{
    public string $rootUrl;

    public Collection $viewer;

    /** @var array<string, mixed> */
    public array $request;
}