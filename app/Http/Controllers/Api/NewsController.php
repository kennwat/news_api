<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Resources\News\NewsListResource;

class NewsController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['author', 'contentBlocks', 'contentBlocks.details'];

    public function index(Request $request)
    {
        $query = News::query()
            ->where('user_id', $request->user()->id);

        // Dynamic loading of relationships via ?include=author,contentBlocks.details
        $query = $this->loadRelationships($query);

        $perPage = $request->input('per_page', 10);
        $news = $query->paginate($perPage);

        return NewsListResource::collection($news);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
