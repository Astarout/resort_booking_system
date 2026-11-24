<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SheetController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'color' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            Sheet::create([
                'color' => $validated['color'],
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Sheet color added successfully!');
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Already exists. Please try again.');
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
            $sheet = Sheet::findOrFail($id);
            $sheet->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Sheet color deleted successfully!');
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Database error while deleting. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }
}
