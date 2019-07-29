<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerChangelogs\Resolvers;

use Vaimo\ComposerChangelogs\Exceptions\PackageResolverException;

class PackageResolver
{
    /**
     * @var \Vaimo\ComposerChangelogs\Repositories\PackageRepository
     */
    private $packageRepository;

    /**
     * @var \Composer\Package\RootPackageInterface
     */
    private $rootPackage;

    /**
     * @param \Vaimo\ComposerChangelogs\Repositories\PackageRepository $packageRepository
     * @param \Composer\Package\RootPackageInterface $rootPackage
     */
    public function __construct(
        \Vaimo\ComposerChangelogs\Repositories\PackageRepository $packageRepository,
        \Composer\Package\RootPackageInterface $rootPackage
    ) {
        $this->packageRepository = $packageRepository;
        $this->rootPackage = $rootPackage;
    }

    /**
     * @param string $packageName
     * @return \Composer\Package\PackageInterface
     * @throws PackageResolverException
     */
    public function resolvePackage($packageName)
    {
        if (!$packageName) {
            $packageName = $this->rootPackage->getName();
        }

        $package = $this->packageRepository->getByName($packageName);

        while ($package instanceof \Composer\Package\AliasPackage) {
            $package = $package->getAliasOf();
        }

        return $package;
    }
}
