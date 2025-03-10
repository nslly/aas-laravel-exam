<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PositionRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Position[]|JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $searchQuery = $request->input('search', '');
        $sortBy = $request->input('sortBy', 'name'); 

        try {

            $positions = $this->positionSortingAndSearching($searchQuery, $sortBy);

            return response()->json($positions);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to retrieve positions', 'error' => $e->getMessage()], 500);
        }
    }




    /**
     * Store a newly created resource in storage.
     * @param PositionRequest $request
     * @return JsonResponse
     */
    public function store(PositionRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $position = new Position();
            $position->name = $validated['name'];
            $position->reports_to = $validated['reports_to'] ?? null;
            $position->save();

            return response()->json($position, 201);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to store position', 'error' => $e->getMessage()], 500);
        }


    }

    /**
     * Display the specified resource.
     * @param Position $position
     * @return JsonResponse
     */
    public function show(Position $position): JsonResponse
    {
        try {
            return response()->json($position);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param PositionRequest $request
     * @param Position $position
     * @return JsonResponse
     */
    public function update(PositionRequest $request, Position $position): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($position->update($validatedData)) {
                return response()->json($position);
            }

            return response()->json(['message' => 'Update failed'], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update position', 'error' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param Position $position 
     * @return JsonResponse
     */
    public function destroy(Position $position): JsonResponse
    {
        try {
            $position->delete();

            return response()->json(['message' => 'Deleted Successfully'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete position', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * function to sort and search for position
     * 
     * @param string $search
     * @param string $sortBy
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function positionSortingAndSearching(string $search, string $sortBy): LengthAwarePaginator
    {
        return $this->searchQuery($search, $sortBy);
    }
    

    /**
     * function to sort and search for position
     * 
     * @param string $search
     * @param string $sortBy
     * @return LengthAwarePaginator
     */
    private function searchQuery(string $search, string $sortBy): LengthAwarePaginator
    {
        $query = Position::query();
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
    
        if ($sortBy) {
            $query->orderBy($sortBy, 'desc');
        } else {
            $query->orderBy('name', 'desc');
        }
    
        return $query->paginate(10);
    }

}
