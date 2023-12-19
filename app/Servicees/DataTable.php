<?php



namespace App\Servicees;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DataTable
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * sort must be send in request as array for example 
     * {
     *       "column": [
     *          "id",
     *          "name"
     *      ],
     *      "asc": [
     *         "desc",
     *         "asc"
     *       ]
     *  }
     */
    private function handleSort()
    {
        if (is_array(request('sort')['column'] ?? [])) {
            foreach (request('sort')['column'] ?? [] as $key => $column) {
                $asc = request('sort')['asc'] ?? [];
                $this->model = $this->model->orderBy($column, $asc[$key] ?? 'asc');
            }
        }
    }

    /**
     * filter must be send in request as array
     * for example 
     * {
     *   "column": [
     *       "name"
     *   ],
     *   "opreator": [
     *       "like" // like , not like , = , != , > , < , >= , <=
     *   ],
     *   "value": [
     *       "Nellie"
     *   ]
     * }
     */
    //  
    private function handleFilter()
    {
        $columns = request('filter')['column'] ?? [];
        foreach ($columns as $key => $column) {
            $operator = request('filter')['opreator'][$key] ?? '=';
            $value = request('filter')['value'][$key] ?? '0';
            $value = $operator == 'like' || 'not like' ? "%$value%" : $value;
            $this->model = $this->model->where($column, $operator, $value);
        }
    }

    public function tableHeader(): array
    {
        $hidden = $this->model->getHidden();
        return collect(Schema::getColumnListing($this->model->getTable()))
            ->filter(fn ($item) => !in_array($item, $hidden))
            ->map(fn ($item) => [
                'label' => strtoupper(str_replace('_', ' ', $item)),
                'value' => $item
            ])
            ->values()
            ->toArray();
    }

    public function query()
    {
        $this->handleFilter();
        $this->handleSort();
        return $this->model;
    }
}
