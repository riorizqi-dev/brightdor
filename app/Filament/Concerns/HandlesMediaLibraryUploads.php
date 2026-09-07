<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;

trait HandlesMediaLibraryUploads
{
    /**
     * Define the mapping of form field names to media collections.
     * Example:
     * [
     *     'cover_image' => 'cover',
     *     'gallery_images' => ['collection' => 'gallery', 'multiple' => true],
     * ]
     */
    protected function getMediaCollectionFields(): array
    {
        return [];
    }

    protected function fillMediaLibraryFields(array $data, ?HasMedia $record): array
    {
        if (! $record) {
            return $data;
        }

        foreach ($this->getMediaCollectionFields() as $field => $config) {
            $collection = is_array($config) ? ($config['collection'] ?? $field) : $config;
            $isMultiple = is_array($config) ? ($config['multiple'] ?? false) : false;

            if ($isMultiple) {
                $data[$field] = $record->getMedia($collection)->map(function ($m) {
                    return $m->id . '/' . $m->file_name;
                })->values()->toArray();
            } else {
                $media = $record->getFirstMedia($collection);
                if ($media) {
                    $data[$field] = $media->id . '/' . $media->file_name;
                }
            }
        }

        return $data;
    }

    protected function saveMediaLibraryFields(?HasMedia $record, array $data): void
    {
        if (! $record) {
            return;
        }

        $disk = Storage::disk('public');

        foreach ($this->getMediaCollectionFields() as $field => $config) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $collection = is_array($config) ? ($config['collection'] ?? $field) : $config;
            $isMultiple = is_array($config) ? ($config['multiple'] ?? false) : false;
            $val = $data[$field] ?? null;

            if ($isMultiple) {
                $submittedPaths = is_array($val) ? array_values(array_filter($val)) : [];

                // 1. Delete media removed by user in form
                foreach ($record->getMedia($collection) as $existing) {
                    $rel = $existing->id . '/' . $existing->file_name;
                    if (! in_array($rel, $submittedPaths, true)) {
                        $existing->delete();
                    }
                }

                // 2. Add newly uploaded files
                foreach ($submittedPaths as $path) {
                    if (is_string($path) && ! preg_match('/^\d+\//', $path) && $disk->exists($path)) {
                        $record->addMediaFromDisk($path, 'public')->toMediaCollection($collection);
                        if ($disk->exists($path)) {
                            $disk->delete($path);
                        }
                    }
                }
            } else {
                $path = is_array($val) ? (reset($val) ?: null) : $val;

                if (blank($path)) {
                    $record->clearMediaCollection($collection);
                } elseif (is_string($path) && ! preg_match('/^\d+\//', $path) && $disk->exists($path)) {
                    $record->clearMediaCollection($collection);
                    $record->addMediaFromDisk($path, 'public')->toMediaCollection($collection);
                    if ($disk->exists($path)) {
                        $disk->delete($path);
                    }
                }
            }
        }
    }
}
