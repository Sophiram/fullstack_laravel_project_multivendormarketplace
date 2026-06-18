<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderShippingController extends Controller
{
    // មុខងាររក្សាទុកព័ត៌មានដឹកជញ្ជូន និងថ្លៃដឹកជាក់ស្តែង
    public function shipOrder(Request $request, $orderId)
    {
        // ១. ពិនិត្យផ្ទៀងផ្ទាត់ទិន្នន័យពី Form
        $request->validate([
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'tracking_number' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0', // 💵 បញ្ចូលថ្លៃដឹកជាក់ស្តែងនៅទីនេះ
            'notes' => 'nullable|string|max:1000'
        ]);

        // ២. រក្សាទុក ឬធ្វើបច្ចុប្បន្នភាពទៅក្នុង Table `shippings`
        Shipping::updateOrCreate(
            ['order_id' => $orderId],
            [
                'shipping_company_id' => $request->shipping_company_id,
                'tracking_number' => $request->tracking_number,
                'shipping_cost' => $request->shipping_cost, // 💵 កត់ត្រាថ្លៃដឹកចូល Table shippings
                'shipping_status' => 'shipped', // ប្តូរស្ថានភាពទៅជាកំពុងដឹក
                'shipped_at' => now(), // កត់ត្រាម៉ោងដែលបានផ្ញើ
                'notes' => $request->notes
            ]
        );

        // ៣. (ជម្រើសបន្ថែម) បច្ចុប្បន្នភាពស្ថានភាពនៅក្នុង Table Orders ធំ (ប្រសិនបើមាន Field status)
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'shipped']);

        return redirect()->back()->with('success', 'Order has been marked as Shipped with tracking info!');
    }
}
