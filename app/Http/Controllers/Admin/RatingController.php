<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function index() {

        $ratings = Rating::latest()
            ->paginate(50);

        return view('admin.ratings', [
            'ratings' => $ratings,
        ]);
    }

    public function visible($id){
        try {
            DB::beginTransaction();

            $review = Rating::findOrFail($id);
            $review->status = 'visible';
            $review->save();

            DB::commit();

            return back()
                ->with('success', 'Review approved!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve review: ' . $e->getMessage());

            return back()
                ->with('error', 'Something went wrong. Review not approved.');
        }
    }

    public function hidden($id){
        try {
            DB::beginTransaction();

            $review = Rating::findOrFail($id);
            $review->status = 'hidden';
            $review->save();

            DB::commit();

            return back()
                ->with('success', 'Review Hidden!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to hide review: ' . $e->getMessage());

            return back()
                ->with('error', 'Something went wrong. Review not deleted.');
        }
    }
}
