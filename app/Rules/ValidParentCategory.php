<?php

namespace App\Rules;

use Closure;
use App\Traits\CategoryQueries;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidParentCategory implements ValidationRule
{
    use CategoryQueries;
    protected $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if ($this->isDescendant($value, $this->categoryId)) {
            $fail('The selected parent category is invalid (circular reference).');
        }
    }


     /**
     * Check if a category is a descendant of another category.
     *
     * @param  int|null  $parentId
     * @param  int  $categoryId
     * @return bool
     */
    protected function isDescendant(?int $parentId, int $categoryId): bool
    {
        while ($parentId) {
            if ($parentId == $categoryId) {
                return true;
            }
            $parentId = DB::table('categories')->where('id' , $parentId)->value('parent_id') ?? null;
        }
        return false;
    }
}
