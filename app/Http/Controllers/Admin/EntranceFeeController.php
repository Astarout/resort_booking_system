<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\EntranceFee;

class EntranceFeeController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'tour_type' => ['required', 'string', 'unique:entrance_fees,tour_type'],
            'amount'=> ['required', 'numeric', 'min:1', 'max:1000'],
        ]);

        DB::beginTransaction();

        try {
            EntranceFee::create($validated);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Entrance Fee added successfully!');
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
            'new_tour_type' => ['required', 'string'],
            'amount'=> ['required', 'numeric', 'min:1', 'max:1000'],
        ]);

        DB::beginTransaction();

        try {
            $entrance_fee = EntranceFee::findOrFail($id);
            $entrance_fee->update($validated);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Entrance fee updated successfully!');
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
            $entrance_fee = EntranceFee::findOrFail($id);
            $entrance_fee->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Entrance fee deleted successfully!');
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
