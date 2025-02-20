<?php

use App\Models\User;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    Process::env(environment: [
        'MAX_USERS' => 1,
        'MAX_RECIPES' => 10
    ])->run(command: 'php artisan db:seed');

    $this->token = User::first()->createToken('access_token')->plainTextToken;
});

describe('GET /recipes', function () {
    it('returns 200 response when requesting a Recipe resource collection', function () {
        $url = route('recipes.index');

        $response = $this->get($url, ['Authorization' => "Bearer $this->token"]);

        expect($response->status())->toBe(Response::HTTP_OK);
    });

    it('returns correct JSON API structure', function () {
        $url = route('recipes.index');

        $response = $this->get($url, ['Authorization' => "Bearer $this->token"]);
        $response->assertExactJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'name',
                        'description',
                        'createdAt',
                        'updatedAt',
                    ],
                    'links' => [
                        'self'
                    ]
                ]
            ],
            'links' => [
                'first', 
                'last', 
                'prev', 
                'next'
            ],
            'meta' => [
                'currentPage', 
                'from', 
                'lastPage', 
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active'
                    ]
                ],
                'path',
                'perPage',
                'to',
                'total'
            ]
        ]);
    });

    it('includes relationships when requested', function () {
        $query = Arr::query([
            'include' => ['user', 'recipeDetail', 'directions', 'ingredients']
        ]);
        $url = route('recipes.index') . '?'. $query;

        $response = $this->get($url, ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'relationships' => [
                        'user', 'recipeDetail', 'directions', 'ingredients'
                    ],
                ]
            ],
            'included' => [
                '*' => [
                    'type',
                    'id',
                    'attributes',
                    'links'
                ]
            ]
        ]);
    });

    it('limits the number of items on a page using query params', function() {
        $url = route('recipes.index');

        $response = $this->get($url, ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonCount(10, 'data');

        $query = Arr::query([
            'page' => [
                'size' => 1
            ]
        ]);

        $urlWithQuery = route('recipes.index') . '?' . $query;

        $response = $this->get($urlWithQuery, ['Authorization' => "Bearer $this->token"]);

        $response->assertJsonCount(1, 'data');
    });
});

afterEach(function () {
    Process::run(command: 'php artisan migrate:fresh');
});