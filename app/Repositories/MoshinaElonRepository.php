<?php

namespace App\Repositories;

use App\Enums\ElonStatus;
use App\Models\MoshinaElon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MoshinaElonRepository
{
    public function __construct(
        private readonly MoshinaElon $model
    ) {}

    public function getFilteredQuery(array $filters = []): Builder
    {
        $query = $this->model->newQuery()
            ->with(['user:id,name,phone,telegram_username', 'category:id,name,slug,icon', 'images'])
            ->where('holati', ElonStatus::Active->value)
            ->latest();

        return $this->applyFilters($query, $filters);
    }

    public function paginate(Builder $query, int $perPage = null): LengthAwarePaginator
    {
        $perPage ??= config('moshina_elon.per_page', 15);

        return $query->paginate(min($perPage, config('moshina_elon.per_page_max', 50)));
    }

    public function findWithRelations(int $id, array $relations = ['user:id,name,phone', 'images']): ?MoshinaElon
    {
        return $this->model->with($relations)->find($id);
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        $filterMap = [
            'category_id' => fn ($v) => $query->where('category_id', $v),
            'marka' => fn ($v) => $query->where('marka', $v),
            'shahar' => fn ($v) => $query->where('shahar', $v),
            'yoqilgi_turi' => fn ($v) => $query->where('yoqilgi_turi', $v),
            'narx_min' => fn ($v) => $query->where('narx', '>=', $v),
            'narx_max' => fn ($v) => $query->where('narx', '<=', $v),
            'yil_min' => fn ($v) => $query->where('yil', '>=', $v),
            'yil_max' => fn ($v) => $query->where('yil', '<=', $v),
        ];

        foreach ($filterMap as $key => $callback) {
            if (isset($filters[$key]) && $filters[$key] !== null && $filters[$key] !== '') {
                $callback($filters[$key]);
            }
        }

        return $query;
    }
}
