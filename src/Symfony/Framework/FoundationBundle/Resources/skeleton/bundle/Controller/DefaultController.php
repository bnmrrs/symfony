<?php

namespace {{ namespace }}\{{ bundle }}\Controller;

use Symfony\Framework\FoundationBundle\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('{{ bundle }}:Default:index');
    }
}
