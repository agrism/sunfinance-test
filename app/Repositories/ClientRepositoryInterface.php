<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function find(int $id): ?Client;

    public function createClient(array $attributes): Client;

    public function update(int $id, array $attributes): ?Client;

    public function delete(int $id): void;

    public function paginate(int $perPage, ?int $page = null): LengthAwarePaginator;
}
