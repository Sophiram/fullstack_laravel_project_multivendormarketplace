<?php

use Livewire\Volt\Component;
use App\Services\KhqrService;

new class extends Component {
    public $qrCodeString;
    public $md5;
    public $amount = 15.5;
    public $orderId = 'ORDER-12345';

    public function mount()
    {
        $service = new KhqrService();
        $result = $service->generateQr($this->amount, 'USD', $this->orderId);

        if ($result['success']) {
            $this->qrCodeString = $result['qr'];
            $this->md5 = $result['md5'];
        }
    }

    public function checkPaymentStatus()
    {
        $service = new KhqrService();
        $result = $service->checkPayment($this->md5);

        if ($result['paid']) {
            $this->redirectRoute('order.success');
        }
    }
}; ?>

<div class="text-center p-5">
    <h3>Please Scan QR Code</h3>

    @if ($qrCodeString)
        <div wire:poll.3s="checkPaymentStatus" class="mt-4">
            {!! QrCode::size(250)->generate($qrCodeString) !!}
        </div>
        <p class="mt-3">Please wait...</p>
    @else
        <p>Something went wrong</p>
    @endif
</div>
