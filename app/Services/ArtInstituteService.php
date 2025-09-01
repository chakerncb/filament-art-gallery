<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ArtInstituteService
{
    private string $baseUrl = 'https://api.artic.edu/api/v1';
    private string $iiifBaseUrl = 'https://www.artic.edu/iiif/2';

    /**
     * Fetch artworks from the Art Institute of Chicago API
     */
    public function getArtworks(int $limit = 12, int $page = 1, array $fields = null): array
    {
        try {
            $cacheKey = "aic_artworks_{$limit}_{$page}";
            
            // Cache for 1 hour to respect API rate limits
            return Cache::remember($cacheKey, 3600, function () use ($limit, $page, $fields) {
                $defaultFields = [
                    'id', 'title', 'artist_display', 'date_display', 'place_of_origin',
                    'description', 'short_description', 'medium_display', 'dimensions',
                    'credit_line', 'is_public_domain', 'image_id', 'artist_title',
                    'artwork_type_title', 'department_title', 'classification_title',
                    'thumbnail', 'main_reference_number'
                ];

                $queryFields = $fields ?? $defaultFields;

                $response = Http::timeout(30)
                    ->withHeaders([
                        'AIC-User-Agent' => 'Laravel Art Gallery'
                    ])
                    ->get($this->baseUrl . '/artworks', [
                        'limit' => $limit,
                        'page' => $page,
                        'fields' => implode(',', $queryFields),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'artworks' => $this->processArtworks($data['data'] ?? []),
                        'pagination' => $data['pagination'] ?? [],
                        'config' => $data['config'] ?? []
                    ];
                }

                Log::error('Failed to fetch artworks from AIC API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return ['artworks' => [], 'pagination' => [], 'config' => []];
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching artworks from AIC API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ['artworks' => [], 'pagination' => [], 'config' => []];
        }
    }

    /**
     * Search artworks by query
     */
    public function searchArtworks(string $query, int $limit = 12, int $page = 1, array $fields = null): array
    {
        try {
            $cacheKey = "aic_search_" . md5($query . $limit . $page);
            
            return Cache::remember($cacheKey, 1800, function () use ($query, $limit, $page, $fields) {
                $defaultFields = [
                    'id', 'title', 'artist_display', 'date_display', 'place_of_origin',
                    'description', 'short_description', 'medium_display', 'dimensions',
                    'credit_line', 'is_public_domain', 'image_id', 'artist_title',
                    'artwork_type_title', 'department_title', 'classification_title',
                    'thumbnail', 'main_reference_number'
                ];

                $queryFields = $fields ?? $defaultFields;

                $response = Http::timeout(30)
                    ->withHeaders([
                        'AIC-User-Agent' => 'Laravel Art Gallery'
                    ])
                    ->get($this->baseUrl . '/artworks/search', [
                        'q' => $query,
                        'size' => $limit,
                        'from' => ($page - 1) * $limit,
                        'fields' => implode(',', $queryFields),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'artworks' => $this->processArtworks($data['data'] ?? []),
                        'pagination' => $data['pagination'] ?? [],
                        'config' => $data['config'] ?? []
                    ];
                }

                return ['artworks' => [], 'pagination' => [], 'config' => []];
            });
        } catch (\Exception $e) {
            Log::error('Exception when searching artworks from AIC API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return ['artworks' => [], 'pagination' => [], 'config' => []];
        }
    }

    /**
     * Get a single artwork by ID
     */
    public function getArtwork(int $id, array $fields = null): ?array
    {
        try {
            $cacheKey = "aic_artwork_{$id}";
            
            return Cache::remember($cacheKey, 7200, function () use ($id, $fields) {
                $defaultFields = [
                    'id', 'title', 'artist_display', 'date_display', 'place_of_origin',
                    'description', 'short_description', 'medium_display', 'dimensions',
                    'credit_line', 'is_public_domain', 'image_id', 'artist_title',
                    'artwork_type_title', 'department_title', 'classification_title',
                    'thumbnail', 'main_reference_number', 'publication_history',
                    'exhibition_history', 'provenance_text'
                ];

                $queryFields = $fields ?? $defaultFields;

                $response = Http::timeout(30)
                    ->withHeaders([
                        'AIC-User-Agent' => 'Laravel Art Gallery'
                    ])
                    ->get($this->baseUrl . "/artworks/{$id}", [
                        'fields' => implode(',', $queryFields),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data'])) {
                        return $this->processArtwork($data['data']);
                    }
                }

                return null;
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching artwork from AIC API', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Build IIIF image URL from artwork ID
     */
    public function buildImageUrl($artworkId, int $width = 843, string $format = 'jpg'): ?string
    {
        if (is_string($artworkId) && preg_match('/^[a-f0-9-]+$/', $artworkId)) {
            return "{$this->iiifBaseUrl}/{$artworkId}/full/{$width},/0/default.{$format}";
        }
        
        $artwork = $this->getArtwork((int)$artworkId);
        if (!$artwork || !isset($artwork['image_id']) || !$artwork['image_id']) {
            return null;
        }
        
        return "{$this->iiifBaseUrl}/{$artwork['image_id']}/full/{$width},/0/default.{$format}";
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrl(?string $imageId, int $width = 843): ?string
    {
        if (!$imageId) {
            return null;
        }

        return $this->buildImageUrl($imageId, $width);
    }

    /**
     * Process multiple artworks
     */
    private function processArtworks(array $artworks): array
    {
        return array_map([$this, 'processArtwork'], $artworks);
    }

    /**
     * Process a single artwork and add computed fields
     */
    private function processArtwork(array $artwork): array
    {
        // Add computed image URLs
        if (isset($artwork['image_id']) && $artwork['image_id']) {
            $artwork['image_url'] = $this->buildImageUrl($artwork['image_id'], 843);
            $artwork['image_url_small'] = $this->buildImageUrl($artwork['image_id'], 400);
            $artwork['image_url_large'] = $this->buildImageUrl($artwork['image_id'], 1200);
        }

        // Clean up description
        if (isset($artwork['description'])) {
            $artwork['description'] = strip_tags($artwork['description']);
        }

        // Ensure artist display or fallback
        if (empty($artwork['artist_display']) && !empty($artwork['artist_title'])) {
            $artwork['artist_display'] = $artwork['artist_title'];
        }

        // Set default values for missing fields
        $artwork['artist_display'] = $artwork['artist_display'] ?? 'Unknown Artist';
        $artwork['date_display'] = $artwork['date_display'] ?? 'Date Unknown';
        $artwork['place_of_origin'] = $artwork['place_of_origin'] ?? 'Origin Unknown';

        return $artwork;
    }

    /**
     * Get featured artworks (high-quality public domain works)
     */
    public function getFeaturedArtworks(int $limit = 8): array
    {
        try {
            $cacheKey = "aic_featured_{$limit}";
            
            return Cache::remember($cacheKey, 7200, function () use ($limit) {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'AIC-User-Agent' => 'Laravel Art Gallery'
                    ])
                    ->get($this->baseUrl . '/artworks/search', [
                        'query' => json_encode([
                            'bool' => [
                                'must' => [
                                    ['term' => ['is_public_domain' => true]],
                                    ['exists' => ['field' => 'image_id']],
                                ]
                            ]
                        ]),
                        'size' => $limit,
                        'fields' => 'id,title,artist_display,date_display,image_id,artist_title,artwork_type_title',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'artworks' => $this->processArtworks($data['data'] ?? []),
                        'pagination' => $data['pagination'] ?? [],
                        'config' => $data['config'] ?? []
                    ];
                }

                return ['artworks' => [], 'pagination' => [], 'config' => []];
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching featured artworks from AIC API', [
                'message' => $e->getMessage()
            ]);

            return ['artworks' => [], 'pagination' => [], 'config' => []];
        }
    }
}
