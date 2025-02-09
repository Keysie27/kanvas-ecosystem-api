<?php

declare(strict_types=1);

namespace Tests\GraphQL\Social;

use Kanvas\AccessControlList\Enums\RolesEnums;
use Kanvas\AccessControlList\Repositories\RolesRepository;
use Kanvas\Apps\Models\Apps;
use Kanvas\Auth\Actions\RegisterUsersAppAction;
use Kanvas\Users\Actions\AssignCompanyAction;
use Kanvas\Users\Models\Users;
use Tests\TestCase;

class FollowTest extends TestCase
{
    /**
     * testFollowUser
     */
    public function testFollowUser(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();
        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);
    }

    /**
     * testUnFollowUser
     */
    public function testUnFollowUser(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();
        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);
        $this->graphQL(
            /** @lang GraphQL */
            '
            mutation userUnFollow(
                $user_id: Int!
            ) {
                userUnFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        )->assertJson(
            [
            'data' => ['userUnFollow' => true],
        ]
        );
    }

    /**
     * testFollowUser
     */
    public function testIsFollowing(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();
        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);
        $response = $this->graphQL(/** @lang GraphQL */
            'query isFollowing($user_id: Int!)
            {
                isFollowing(
                    user_id: $user_id
                )
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['isFollowing' => true],
        ]);
    }

    /**
     * testGetFollowers
     */
    public function testGetFollowers(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();
        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
            query getFollowers($user_id: Int!)
            {
                getFollowers(
                    user_id: $user_id
                )
                {
                    data {
                        email
                    }
                }
            }
            ',
            [
                'user_id' => $user->id,
            ]
        )->assertJson(
            [
            'data' => [
                'getFollowers' => [
                    'data' => [
                        [
                            'email' => auth()->user()->email,
                        ],
                    ],
                ],
            ],
        ]
        );
    }

    public function testGetTotalFollowers(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();
        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
            query getTotalFollowers($user_id: Int!)
            {
                getTotalFollowers(
                    user_id: $user_id
                )
            }
            ',
            [
                'user_id' => $user->id,
            ]
        )->assertJson(
            [
            'data' => [
                'getTotalFollowers' => 1,
            ],
        ]
        );
    }

    /**
     * testGetFollowing
     */
    public function testGetFollowing(): void
    {
        $user = Users::factory()->create();
        $branch = auth()->user()->getCurrentBranch();

        (new RegisterUsersAppAction($user, app(Apps::class)))->execute($user->password);
        //add user to current company
        (new AssignCompanyAction(
            $user,
            $branch,
            RolesRepository::getByNameFromCompany(RolesEnums::ADMIN->value),
            app(Apps::class)
        ))->execute();

        $response = $this->graphQL(/** @lang GraphQL */
            '
            mutation userFollow(
                $user_id: Int!
            ) {
                userFollow(user_id: $user_id)
            }
            ',
            [
                'user_id' => $user->id,
            ]
        );
        $response->assertJson([
            'data' => ['userFollow' => true],
        ]);

        $this->graphQL(
            /** @lang GraphQL */
            '
            query getFollowing($user_id: Int!)
            {
                getFollowing(
                    user_id: $user_id
                )
                {
                    data {
                        entity {
                            email
                        }
                    }
                }
            }
            ',
            [
                'user_id' => auth()->user()->id,
            ]
        )->assertJsonFragment(
            [
                'entity' => [
                    'email' => $user->email,
                ],
            ]
        );
    }
}
