<?php

namespace App\Repositories\Eloquent;

use App\Models\Client;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function find(int $id): ?Client
    {
        return Client::query()->where('id', $id)->first();
    }

    public function createClient(array $attributes): Client
    {
        return Client::query()->create($attributes);
    }

    public function update(int $id, array $attributes): ?Client
    {
        if (!$client = Client::query()->where('id', $id)->first()) {
            return null;
        }

        $client->update($attributes);

        return $client;
    }

    public function delete(int $id): void
    {
        Client::query()->where('id', $id)->delete();
    }

    public function paginate(int $perPage, ?int $page = null): LengthAwarePaginator
    {
        return Client::query()->paginate(perPage: $perPage, page: $page);
    }
}
