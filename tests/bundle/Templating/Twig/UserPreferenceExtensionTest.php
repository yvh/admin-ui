<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Taxonomy\Twig;

use Ibexa\Bundle\AdminUi\Templating\Twig\UserPreferenceExtension;
use Ibexa\Bundle\AdminUi\Templating\Twig\UserPreferenceRuntime;
use Ibexa\Contracts\Core\Repository\UserPreferenceService;
use Ibexa\Contracts\Core\Repository\Values\UserPreference\UserPreference;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\Test\IntegrationTestCase;

final class UserPreferenceExtensionTest extends IntegrationTestCase
{
    protected function getRuntimeLoaders(): array
    {
        $userPreferenceService = $this->createUserPreferenceService();

        return [
            new class($userPreferenceService) implements RuntimeLoaderInterface {
                private UserPreferenceService $userPreferenceService;

                public function __construct(
                    UserPreferenceService $userPreferenceService
                ) {
                    $this->userPreferenceService = $userPreferenceService;
                }

                public function load(string $class): ?RuntimeExtensionInterface
                {
                    if ($class === UserPreferenceRuntime::class) {
                        return new UserPreferenceRuntime($this->userPreferenceService);
                    }

                    return null;
                }
            },
        ];
    }

    protected function getFixturesDir(): string
    {
        return __DIR__ . '/_fixtures/user_preference/';
    }

    /**
     * @return \Twig\Extension\ExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        return [
            new UserPreferenceExtension(),
        ];
    }

    private function createUserPreferenceService(): UserPreferenceService
    {
        $userPreference = new UserPreference([
            'value' => 'bar',
        ]);
        $callback = static function ($identifier) use ($userPreference): UserPreference {
            if ($identifier == 'baz') {
                throw new NotFoundException('User Preference', 14);
            }

            return $userPreference;
        };

        $userPreferenceService = $this->createMock(UserPreferenceService::class);
        $userPreferenceService
            ->method('getUserPreference')
            ->willReturnCallback($callback);

        return $userPreferenceService;
    }
}
