<?php

declare(strict_types=1);

namespace Kanvas\Guild\Customers\DataTransferObject;

use Baka\Contracts\AppInterface;
use Baka\Users\Contracts\UserInterface;
use Kanvas\Companies\Models\CompaniesBranches;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class People extends Data
{
    /**
     * __construct.
     */
    public function __construct(
        public readonly AppInterface $app,
        public readonly CompaniesBranches $branch,
        public readonly UserInterface $user,
        public readonly string $firstname,
        public readonly string $lastname,
        /** @var Kanvas\Guild\Customers\DataTransferObject\Contact[] */
        public readonly DataCollection|Optional $contacts,
        /** @var Kanvas\Guild\Customers\DataTransferObject\Address[] */
        public readonly DataCollection|Optional $address,
        public readonly int $id = 0,
        public readonly ?string $dob = null,
        public readonly ?string $facebook_contact_id = null,
        public readonly ?string $google_contact_id = null,
        public readonly ?string $apple_contact_id = null,
        public readonly ?string $linkedin_contact_id = null,
    ) {
    }
}
