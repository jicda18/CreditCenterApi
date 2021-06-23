<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;

class NotificationResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $company_uuid = $this->getRelationUuid('company_id');

        return $this->includeHistory($request, [
            'key' => $this->uuid,
            'company_key' => $company_uuid,
            'lvl' => $this->level,
            'msg' => $this->text,
            'inserted_at' => $this->created_at->toIso8601String(),
            'edited_at' => $this->updated_at->toIso8601String(),
            'removed_at' => null_prevent_function($this->deleted_at, 'toIso8601String'),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('notifications.show', $this->resource),
                ],
                [
                    'rel' => 'company',
                    'href' => route('companies.show', $company_uuid),
                ],
            ],
        ]);
    }

    /**
     * Get hash resource's keys to model's keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hash(): Collection
    {
        return collect([
            'key' => 'uuid',
            'company_key' => 'company_uuid',
            'lvl' => 'level',
            'msg' => 'text',
            'inserted_at' => 'created_at',
            'edited_at' => 'updated_at',
            'removed_at' => 'deleted_at',
        ]);
    }
}
