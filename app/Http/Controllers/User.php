<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as UserModel;

class User extends Controller
{

    public function listAllApi()
    {
      $listAll = UserModel::all();

      return response()
        ->json(['list' => $listAll])
        ->withHeaders([
          'myHeader' => 'qwerty'
        ])
        ;
    }
}
