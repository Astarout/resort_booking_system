<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\AccommodationGallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Pet;
use App\Models\Sheet;
use App\Models\EntranceFee;

class AccommodationController extends Controller
{
    public function index() {
        $accommodations = Accommodation::all();
        $allowed_pets = Pet::all();
        $available_sheets = Sheet::all();
        $entrance_fees = EntranceFee::all();

        # dd($accommodations, $available_sheets, $allowed_pets);

        return view('admin.accommodation.accommodation', [
            'accommodations' => $accommodations,
            'allowed_pets' => $allowed_pets,
            'available_sheets' => $available_sheets,
            'entrance_fees' => $entrance_fees,
        ]);
    }

    public function addForm() {
        return view('admin.accommodation.add');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'pax' => ['required', 'integer', 'min:1'],
            'tour_type' => ['nullable', 'in:day,night'],
            'check_in_time' => ['required'],
            'check_out_time' => ['required'],
            'room_count' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // create accommodation first
            $accommodation = Accommodation::create([
                'name' => $validated['name'],
                'pax' => $validated['pax'],
                'tour_type' => $validated['tour_type'] ?? null,
                'check_in_time' => $validated['check_in_time'],
                'check_out_time' => $validated['check_out_time'],
                'room_count' => $validated['room_count'],
                'price' => $validated['price'],
                'discount' => $validated['discount'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $folderName = 'accommodations/' . $accommodation->name;

            if (!empty($accommodation->tour_type)) {
                $folderName .= '-' . $accommodation->tour_type;
            }

            // handle main image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store($folderName, 'public');
                $accommodation->update(['image' => $imagePath]);
            }

            // handle gallery images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store($folderName . '/gallery', 'public');
                    AccommodationGallery::create([
                        'accommodation_id' => $accommodation->id,
                        'image' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.accommodation')
                ->with('success', 'Accommodation created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while saving: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        $accommodation = Accommodation::findOrFail($id);

        // Build the same folder name as in 'store'
        $folderName = 'accommodations/' . $accommodation->name;
        if (!empty($accommodation->tour_type)) {
            $folderName .= '-' . $accommodation->tour_type; // match store logic
        }

        try {
            DB::beginTransaction();

            // Delete from DB first
            $accommodation->delete();

            DB::commit();

            // Delete the folder from storage
            if (Storage::disk('public')->exists($folderName)) {
                Storage::disk('public')->deleteDirectory($folderName);
            }

            return redirect()
                ->route('admin.accommodation')
                ->with('success', 'Accommodation deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete accommodation: ' . $e->getMessage()]);
        }
    }

    public function editForm($id) {
        $accommodation = Accommodation::findOrFail($id);

        $gallery = AccommodationGallery::where('accommodation_id', $id)->get();

        return view('admin.accommodation.edit', [
            'accommodation' => $accommodation,
            'gallery' => $gallery
        ]);
    }

    public function update(Request $request, $id) {
        $accommodation = Accommodation::findOrFail($id);

        $folderName = 'accommodations/' . $accommodation->name;

        if (!empty($accommodation->tour_type)) {
            $folderName .= '-' . $accommodation->tour_type;
        }

        try {
            DB::beginTransaction();

            // Update main image if uploaded
            if ($request->hasFile('image')) {
                // Delete old main image if exists
                if ($accommodation->image && Storage::disk('public')->exists($accommodation->image)) {
                    Storage::disk('public')->delete($accommodation->image);
                }

                $path = $request->file('image')->store($folderName, 'public');
                $accommodation->image = $path;
            }

            // Update other fields
            $accommodation->update($request->except(['image', 'gallery', 'old_gallery']));

            // Handle gallery
            $oldGalleryUrls = $request->input('old_gallery', []);
            $currentGallery = AccommodationGallery::where('accommodation_id', $id)->get();

            // Delete removed gallery images
            foreach ($currentGallery as $img) {
                if (!in_array(asset('storage/' . $img->image), $oldGalleryUrls)) {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                    $img->delete();
                }
            }

            // Add new gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store($folderName . '/gallery', 'public');
                    AccommodationGallery::create([
                        'accommodation_id' => $id,
                        'image' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.accommodation')
                ->with('success', 'Accommodation updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update accommodation: ' . $e->getMessage()]);
        }
    }
}
