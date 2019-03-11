<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Siteaccess;

class AdminSiteaccessPreviewVoter extends AbstractSiteaccessPreviewVoter
{
    /**
     * {@inheritdoc}
     */
    public function getRootLocationIds(string $siteaccess): array
    {
        $locationIds = [];
        $locationIds[] = $this->configResolver->getParameter(
            'location_ids.content_structure',
            null,
            $siteaccess
        );
        $locationIds[] = $this->configResolver->getParameter(
            'location_ids.media',
            null,
            $siteaccess
        );
        $locationIds[] = $this->configResolver->getParameter(
            'location_ids.users',
            null,
            $siteaccess
        );

        return $locationIds;
    }
}
