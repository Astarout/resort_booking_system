<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index() {
        $galleries = Gallery::paginate(25);

        return view('admin.gallery', [
            'galleries' => $galleries,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'image' => ['required', 'image', 'max:8192'],
            'type' => ['required'],
            'description' => ['nullable', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $folder = 'galleries/' . $request->type;
            $path = $request->file('image')->store($folder, 'public');

            Gallery::create([
                'image' => $path,
                'type' => $request->type,
                'description' => $request->description,
            ]);

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Image uploaded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Gallery $gallery) {
        DB::beginTransaction();

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }

            // Delete DB record
            $gallery->delete();

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Image deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    public function updateImage(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:8192'], // optional
            'type' => ['required'],
            'description' => ['nullable', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'type' => $request->type,
                'description' => $request->description,
            ];

            // Case 1: New image uploaded
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if (Storage::disk('public')->exists($gallery->image)) {
                    Storage::disk('public')->delete($gallery->image);
                }

                // Save new image
                $folder = 'galleries/' . $request->type;
                $path = $request->file('image')->store($folder, 'public');
                $data['image'] = $path;
            }
            // Case 2: No new image, but type has changed (move file)
            elseif ($gallery->type !== $request->type) {
                $oldPath = $gallery->image;
                $fileName = basename($oldPath);
                $newFolder = 'galleries/' . $request->type;
                $newPath = $newFolder . '/' . $fileName;

                if (Storage::disk('public')->exists($oldPath)) {
                    // Ensure new folder exists
                    Storage::disk('public')->makeDirectory($newFolder);

                    // If file already exists in new folder, rename to avoid overwrite
                    if (Storage::disk('public')->exists($newPath)) {
                        $fileInfo = pathinfo($fileName);
                        $fileBase = $fileInfo['filename'];
                        $fileExt = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
                        $counter = 1;

                        // Generate unique name
                        while (Storage::disk('public')->exists($newFolder . '/' . $fileBase . '_' . $counter . $fileExt)) {
                            $counter++;
                        }

                        $newPath = $newFolder . '/' . $fileBase . '_' . $counter . $fileExt;
                    }

                    // Move file
                    Storage::disk('public')->move($oldPath, $newPath);
                    $data['image'] = $newPath;
                }
            }

            // Update DB record
            $gallery->update($data);

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Image updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($newPath) && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }
            if (!empty($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to update image: ' . $e->getMessage());
        }
    }
}
