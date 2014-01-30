<?php

namespace Infostander\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfostanderUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
