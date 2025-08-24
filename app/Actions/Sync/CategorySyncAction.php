<?php

namespace App\Actions\Sync;

use App\DTO\CategoryDTO;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategorySyncAction
{
/**
*
* @param CategoryDTO[] $categoriesDTO.
*/
    public function execute(array $categoriesDTO)
    {
        foreach ($categoriesDTO as $categoryDTO) {
            try {
                Category::updateOrCreate(
                    ['name' => $categoryDTO->name],
                    []
                );
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
