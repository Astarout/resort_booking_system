<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PetController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u', 'unique:pets,name'],
            'capacity'=> ['required', 'integer', 'min:1', 'max:10'],
        ]);

        DB::beginTransaction();

        try {
            Pet::create($validated);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pet added successfully!');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Database error. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'pet_name' => ['required', 'string', 'max:50'],
            'capacity'=> ['required', 'integer', 'min:1', 'max:10'],
        ]);

        DB::beginTransaction();

        try {
            $pet = Pet::findOrFail($id);
            $pet->update($validated);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pet updated successfully!');
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Database error. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function destroy($id) {
        DB::beginTransaction();

        try {
            $pet = Pet::findOrFail($id);
            $pet->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pet deleted successfully!');
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Database error. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }
}
