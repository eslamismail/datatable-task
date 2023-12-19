<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Servicees\DataTable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public $datatable;
    public $datatable;
    public function __construct(User $user)
    {
        $this->datatable = new DataTable($user);
    }

    public function index()
    {
        $response['header'] = $this->datatable->tableHeader();
        $response['data'] = $this->datatable->query()->paginate(request('pre_page'));

        return $this->response($response);
    }
}
