<?php
/**
 * @file
 * This file is a part of the Infostander UserBundle which is a modification of the FOSUserBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infostander\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class InfostanderUserBundle
 *
 * Extends the FOSUserBundle.
 *
 * @package Infostander\UserBundle
 */
class InfostanderUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
