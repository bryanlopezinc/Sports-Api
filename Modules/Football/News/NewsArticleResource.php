<?php

declare(strict_types=1);

namespace Module\Football\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class NewsArticleResource extends JsonResource
{
    public function __construct(private NewsArticle $newsArticle)
    {
        parent::__construct($newsArticle);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'       => 'football_news',
            'attributes' => [
                'title'    => $this->newsArticle->title->value(),
                'body'     => $this->newsArticle->shortDescription,
                'link'     => $this->newsArticle->linkToFullArticle,
            ],
        ];
    }
}
