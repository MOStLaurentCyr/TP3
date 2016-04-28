<?php

namespace Cimmi\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CimmiAuthBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
