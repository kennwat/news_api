<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\News\NewsListResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\News;
use Illuminate\Http\Request;

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

        $query
            ->when($request->input('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->whereLike('title->de', "%{$search}%")
                        ->orWhereLike('title->en', "%{$search}%")
                        ->orWhereLike('short_description->de', "%{$search}%")
                        ->orWhereLike('short_description->en', "%{$search}%");
                });
            })
            ->when($request->filled('is_visible'), function ($q) use ($request) {
                $q->where('is_visible', $request->boolean('is_visible'));
            })
            ->orderBy('created_at', 'desc');

        // Pagination
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
