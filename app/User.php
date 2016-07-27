<?php

namespace App;

use Vinkas\Firebase\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = "users";

}
