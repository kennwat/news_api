<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsStoreRequest;
use App\Http\Requests\News\NewsUpdateRequest;
use App\Http\Resources\News\NewsListResource;
use App\Http\Resources\News\NewsResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\HasSlug;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    use CanLoadRelationships, HasSlug;

    private array $relations = ['author', 'contentBlocks', 'contentBlocks.details'];

    public function index(Request $request): AnonymousResourceCollection
    {
        // $user = $request->user();
        $user = auth('sanctum')->user();
        $query = News::query();

        // Filtration based on user authentication
        if ($user) {
            // AUTHORIZED: own news (all) + others' visible news
            if ($request->filled('author')) {
                $authorId = (int) $request->input('author');

                if ($authorId === $user->id) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('user_id', $authorId)
                        ->where('is_visible', true);
                }
            } else {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere('is_visible', true);
                });
            }
        } else {
            // UNAUTHORIZED: only visible and published news
            $query->visible()->published();

            if ($request->filled('author')) {
                $query->where('user_id', $request->input('author'));
            }
        }

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
            ->when($request->input('date'), function ($q) use ($request) {
                $q->whereDate('published_at', $request->date);
            })
            ->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->input('per_page', 10);
        $news = $query->paginate($perPage);

        return NewsListResource::collection($news);
    }

    public function store(NewsStoreRequest $request): NewsResource
    {
        $data = $request->validated();

        if (! isset($data['slug'])) {
            $data['slug'] = self::generateUniqueSlug($data['title']['en'], News::class);
        }

        $data['user_id'] = auth()->id();
        $data['is_visible'] = $data['is_visible'] ?? true;

        $contentBlocksData = $data['content_blocks'] ?? [];
        unset($data['content_blocks']);

        // Create news with blocks and details in a transaction
        $news = DB::transaction(function () use ($data, $contentBlocksData) {
            $news = News::create($data);

            foreach ($contentBlocksData as $blockData) {
                $detailsData = $blockData['details'] ?? [];
                unset($blockData['details']);

                $block = $news->contentBlocks()->create([
                    'type' => $blockData['type'],
                    'position' => $blockData['position'],
                ]);

                foreach ($detailsData as $detail) {
                    $block->details()->create($detail);
                }
            }

            return $news;
        });

        $news = $this->loadRelationships($news->fresh());

        return new NewsResource($news);
    }

    public function show(News $news): NewsResource
    {
        Gate::authorize('view', $news);

        $news = $this->loadRelationships($news);

        return new NewsResource($news);
    }

    public function update(NewsUpdateRequest $request, News $news): NewsResource
    {
        Gate::authorize('update', $news);

        $data = $request->validated();
        unset($data['slug']);

        $contentBlocksData = $data['content_blocks'] ?? null;
        unset($data['content_blocks']);

        DB::transaction(function () use ($news, $data, $contentBlocksData) {
            $news->update($data);

            // soft delete existing blocks and details, then recreate
            if ($contentBlocksData !== null) {
                $news->contentBlocks()->each(function ($block) {
                    $block->details()->delete();
                    $block->delete();
                });

                foreach ($contentBlocksData as $blockData) {
                    $detailsData = $blockData['details'] ?? [];
                    unset($blockData['details']);

                    $block = $news->contentBlocks()->create([
                        'type' => $blockData['type'],
                        'position' => $blockData['position'],
                    ]);

                    foreach ($detailsData as $detail) {
                        $block->details()->create($detail);
                    }
                }
            }
        });

        return new NewsResource($this->loadRelationships($news->fresh()));
    }

    public function destroy(News $news): JsonResponse
    {
        Gate::authorize('delete', $news);

        // Soft delete (cascading)
        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
