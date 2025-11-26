<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHousePreviewRequest;
use App\Models\HousePreview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HousePreviewController extends Controller
{
    /**
     * Display a listing of house previews.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage =  $request->get('per_page', 15);
        $status = $request->get('status');
        
        $query = HousePreview::query()->latest();
        
        if ($status && in_array($status, ['pending', 'processing', 'completed'])) {
            $query->where('status', $status);
        }
        
        $previews = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $previews,
        ]);
    }

    /**
     * Store a newly created house preview.
     *
     * @param StoreHousePreviewRequest $request
     * @return JsonResponse
     */
    public function store(StoreHousePreviewRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $customerData = $validated['customer'];
            
            // Find existing customer by phone (unique identifier)
            $customer = \App\Models\Customer::where('phone', $customerData['phone'])->first();
            
            // Create new customer if not found
            if (!$customer) {
                $customer = \App\Models\Customer::create($customerData);
            }
            
            // Handle image upload
            $houseImagePath = null;
            if ($request->hasFile('house_image')) {
                $image = $request->file('house_image');
                $houseImagePath = $image->store('house_previews/images', 'public');
            }
            
            // Handle SVG image upload
            $svgImagePath = null;
            if ($request->hasFile('svg_image')) {
                $image = $request->file('svg_image');
                $svgImagePath = $image->store('house_previews/svg', 'public');
            }
            
            $housePreview = \App\Models\HousePreview::create([
                'customer_id' => $customer->id,
                'colors' => $validated['colors'] ?? null,
                'house_image' => $houseImagePath,
                'svg_image' => $svgImagePath,
                'customer_message' => $validated['customer_message'] ?? null,
            ]);
            
            $housePreview->load(['customer']);
            
            return response()->json([
                'success' => true,
                'message' => 'House preview submitted successfully',
                'data' => $housePreview,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create house preview',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified house preview.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $housePreview = HousePreview::find($id);
        
        if (!$housePreview) {
            return response()->json([
                'success' => false,
                'message' => 'House preview not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $housePreview,
        ]);
    }

    /**
     * Update the specified house preview.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $housePreview = HousePreview::find($id);
        
        if (!$housePreview) {
            return response()->json([
                'success' => false,
                'message' => 'House preview not found',
            ], 404);
        }
        
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);
        
        $housePreview->update([
            'status' => $request->status,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'House preview status updated successfully',
            'data' => $housePreview,
        ]);
    }

    /**
     * Remove the specified house preview.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $housePreview = HousePreview::find($id);
        
        if (!$housePreview) {
            return response()->json([
                'success' => false,
                'message' => 'House preview not found',
            ], 404);
        }
        
        if ($housePreview->house_image && Storage::disk('public')->exists($housePreview->house_image)) {
            Storage::disk('public')->delete($housePreview->house_image);
        }
        
        if ($housePreview->svg_image && Storage::disk('public')->exists($housePreview->svg_image)) {
            Storage::disk('public')->delete($housePreview->svg_image);
        }
        
        $housePreview->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'House preview deleted successfully',
        ]);
    }
}
