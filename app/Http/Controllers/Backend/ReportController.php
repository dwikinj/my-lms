<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    function ReportView()
    {
        return view('admin.backend.report.report_view');
    } //end method

    public function SearchByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $date = new DateTime($request->date);
            $formattedDate = $date->format('d F Y');

            $payments = Payment::where('order_date', $formattedDate)
                ->latest()
                ->get();

            return view('admin.backend.report.report_by_date', compact('formattedDate', 'payments'));
        } catch (\Exception $e) {
            $notification = [
                'message' => 'Invalid date format provided. Please use YYYY-MM-DD format.',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }
    } //end method

    public function SearchByMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'year' => 'required|integer|min:2025|max:' . (date('Y') + 5)
        ]);
        $selectedMonth = $validated['month'];
        $selectedYear =  $validated['year'];

        $payments = Payment::query()
            ->where('order_month', $selectedMonth)
            ->where('order_year', $selectedYear)
            ->latest()
            ->get();

        return view('admin.backend.report.report_by_month', compact('payments', 'selectedMonth', 'selectedYear'));
    } //end method

    public function SearchByYear(Request $request)
    {
        $validated = $request->validate([
            'year_name' => 'required|integer|min:2025|max:' . (date('Y') + 5)
        ]);
        $selectedYear = $validated['year_name'];

        $payments = Payment::query()
            ->where('order_year', $selectedYear)
            ->latest()
            ->get();

        return view('admin.backend.report.report_by_year', compact('payments', 'selectedYear'));
    } //end method
}
