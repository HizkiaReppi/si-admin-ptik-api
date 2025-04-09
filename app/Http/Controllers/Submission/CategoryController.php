<?php

namespace App\Http\Controllers\Submission;

use App\Classes\ApiResponseClass;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Submission\StoreCategoryRequest;
use App\Http\Requests\Submission\UpdateCategoryRequest;
use App\Services\Submission\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected ApiResponseHelper $apiResponseHelper,
        protected ApiResponseClass $apiResponseClass
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->only('search');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', null);
        $order = $request->input('order');

        $filters = [
            'search' => $search['search'] ?? null,
            'sortBy' => $sortBy,
            'order' => $order,
        ];

        $categories = $this->categoryService->getAll($filters, (int) $perPage);

        $pagination = $this->apiResponseHelper->generatePagination($categories);
        $categories = $categories->items();

        return $this->apiResponseClass->sendResponseWithPagination(200, 'Categories retrieved successfully', $categories, $pagination);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $category = $this->categoryService->create($validatedData);

            return $this->apiResponseClass->sendResponse(201, 'Category created successfully', $category->toArray());
        } catch (\Exception $e) {
            if($e->getCode() === 409) {
                return $this->apiResponseClass->sendError(409, $e->getMessage());
            } else {
                return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        try {
            $category = $this->categoryService->getById($category->id);
            return $this->apiResponseClass->sendResponse(200, 'Category retrieved successfully', $category->toArray());
        } catch (ResourceNotFoundException $e) {
           if($e->getCode() === 409) {
                return $this->apiResponseClass->sendError(409, $e->getMessage());
            } else {
                return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $validatedData = $request->validated();
        try {
            $category = $this->categoryService->update($validatedData, $category->id);

            return $this->apiResponseClass->sendResponse(200, 'Category updated successfully', $category->toArray());
        } catch (ResourceNotFoundException $e) {
            return $this->apiResponseClass->sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            if($e->getCode() === 409) {
                return $this->apiResponseClass->sendError(409, $e->getMessage());
            } else {
                return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->delete($category->id);
            return $this->apiResponseClass->sendResponse(200, 'Category deleted successfully');
        } catch (ResourceNotFoundException $e) {
            return $this->apiResponseClass->sendError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->apiResponseClass->sendError(500, 'An error occurred. Please try again later.');
        }
    }
}
