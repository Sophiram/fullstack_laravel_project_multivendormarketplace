<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KhqrService; // ហៅ Service ដែលបងទើបបង្កើតមកប្រើ

class BakongPaymentController extends Controller
{
    protected $khqrService;

    // ចាក់បញ្ចូល (Inject) KhqrService ចូលក្នុង Constructor
    public function __construct(KhqrService $khqrService)
    {
        $this->khqrService = $khqrService;
    }

    /**
     * 1. មុខងារសម្រាប់បង្កើត QR Code ឱ្យ Frontend (React) យកទៅបង្ហាញ
     */
    public function generateQR(Request $request)
    {
        $amount   = $request->input('amount');   // ទឹកប្រាក់ ឧទាហរណ៍៖ 15.50
        $currency = $request->input('currency', 'USD'); // ប្រភេទលុយ 'USD' ឬ 'KHR'

        // ហៅមុខងារបង្កើត QR ពី Service (វាដំណើរការលឿនណាស់ព្រោះអត់ហៅ API ក្រៅទេ)
        $result = $this->khqrService->generateQr($amount, $currency);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'qr_code' => $result['qr'],   // កូដសម្រាប់យកទៅគូរជា QR Image លើ React
                'md5'     => $result['md5'],  // រក្សាទុកលើ React សម្រាប់ប្រើ Check Payment
            ]);
        }

        return response()->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * 2. មុខងារសម្រាប់ឱ្យ React ហៅមកសួររាល់ 2-3 វិនាទីម្តង (Polling) ថាគេបង់លុយនៅ?
     */
    public function checkStatus(Request $request)
    {
        $md5 = $request->input('md5');

        if (empty($md5)) {
            return response()->json(['success' => false, 'message' => 'Missing MD5 hash'], 400);
        }

        // ហៅទៅសួរ API របស់ Bakong តាមរយៈ Service
        $status = $this->khqrService->checkPayment($md5);

        if ($status['paid']) {
            // TODO: សរសេរកូដកែប្រែស្ថានភាព Order ក្នុង DB ទៅជា 'paid' ឬ 'completed' នៅត្រង់នេះ

            return response()->json([
                'success' => true,
                'paid'    => true,
                'message' => 'ការទូទាត់ប្រាក់ទទួលបានជោគជ័យ!',
                'data'    => $status['data'] // ព័ត៌មានលម្អិតពីធនាគារ
            ]);
        }

        return response()->json([
            'success' => true,
            'paid'    => false,
            'message' => 'កំពុងរង់ចាំការទូទាត់...'
        ]);
    }
}
