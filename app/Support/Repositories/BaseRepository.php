<?php

namespace App\Support\Repositories;

use App\Exceptions\RepositoryResourceFailedException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class BaseRepository implements IRepository
{
    protected int $perPage = 8;
    protected $model;
    protected string $sortDirection = "asc";
    protected string $sortColumn = "id";
    protected Builder $query;

    public function modelQuery()
    {
        return (new $this->model)->query();
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function create(array $data)
    {
        if (sizeof($data) >= 2) {
            $this->createMany($data);
        } else {
            $this->modelQuery()->create($data[0]);
        }

        return $this;
    }

    public function createMany(array $data)
    {
        try {
            $beforeInsertionId = DB::table($this->modelQuery()->from)->count("id");

            DB::beginTransaction();

            $createdAt = Carbon::now();
            for ($j = 0; $j < sizeof($data); $j++) {
                $data[$j]["created_at"] = $createdAt;
            }
            DB::table("files")->insert($data);

            $lastInsertedIds = [];

            for ($i = 1; $i <= sizeof($data); $i++) {
                $lastInsertedIds[] = $beforeInsertionId+$i;
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            throw new RepositoryResourceFailedException("Failed while creating resource: {$e->getMessage()}");
        }

        $this->query = $this->modelQuery()->whereIntegerInRaw("id", $lastInsertedIds);
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function setOrderDirection(string $direction)
    {
        if ($direction == "asc" || $direction == "desc") {
            $this->sortDirection = $direction;
        } else {
            throw new RepositoryResourceFailedException("Invalid order direction");
        }
    }

    public function setColumn(string $column)
    {
        if ($this->isColumnExists($column)) {
            $this->sortColumn = $column;
        } else {
            throw new RepositoryResourceFailedException("Invalid field sortBy. column: '{$column}' does not exist in table: '{$this->query->getModel()->getTable()}'", 400);
        }
    }

    private function isColumnExists(string $column): bool
    {
        // getting columns of current model.
        $modelColumns = Schema::getColumnListing($this->modelQuery()->from);

        return array_key_exists($column, array_flip($modelColumns));
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function sortBy(?string $column = "id", ?string $direction = "asc")
    {
        if (!is_null($column)) {
            $this->setColumn($column);
        }

        if (!is_null($direction)) {
            $this->setOrderDirection($direction);
        }

        return $this;
    }

    public function paginate(?int $num = 0) : LengthAwarePaginator
    {
        if (is_numeric($num) && $num > 0) {
            return $this->query->paginate($num);
        }

        return $this->query->orderBy($this->sortColumn, $this->sortDirection)->paginate($this->perPage);
    }

    public function queryAll()
    {
        return $this->query->get();
    }


    public function getAll()
    {
        $this->query = $this->modelQuery();

        return $this;
    }

    /**
     * @param $id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws RepositoryResourceFailedException
     */
    public function findById($id)
    {
        $this->query = $this->modelQuery()->where("id", "=", $id);

        if (!$this->query->exists()) {
            throw new RepositoryResourceFailedException("Resource was not found", 404);
        }

        return $this->query->get();
    }

    /**
     * @param string $value
     * @return $this
     */
    public function findByName(string $value)
    {
        $res = $this->query = $this->modelQuery()->whereFullText("name", $value);

        return $this;
    }
}
