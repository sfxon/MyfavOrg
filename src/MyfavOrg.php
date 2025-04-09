<?php

namespace Myfav\Org;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;

/**
 * MyfavImp
 */
class MyfavOrg extends Plugin
{
        /**
     * install
     *
     * @param  InstallContext $installContext
     * @return void
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
    }

    /**
     * uninstall
     *
     * @param  UninstallContext $context
     * @return void
     */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }
    }

    /**
     * update
     *
     * @param  UpdateContext $updateContext
     * @return void
     */
    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);
    }
}