<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDataTableRequest;
use App\Models\User;
use App\Servicees\DataTable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $datatable;
    public function __construct(User $user)
    {
        $this->datatable = new DataTable($user);
    }

    public function index(UserDataTableRequest $request)
    {
        $response['header'] = $this->datatable->tableHeader();
        $response['data'] = $this->datatable->query()->paginate(request('per_page'));

        return $this->response($response);
    }
}
