<?php

declare(strict_types=1);

namespace Arakviel\App\Core;

use Arakviel\DB\Factory\DAOFactoryImpl;

abstract class Model
{

    protected DAOFactoryImpl $db;

    public function __construct()
    {
        $this->db = DAOFactoryImpl::getInstance();
    }
}
