<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class PaymentController extends Controller
{
    public function payNow()
    {
        $post_data = [
            'total_amount' => 100,
            'currency' => 'BDT',
            'tran_id' => uniqid(),

            'cus_name' => 'John Doe',
            'cus_email' => 'john@example.com',
            'cus_add1' => 'Dhaka',
            'cus_add2' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01711111111',

            'ship_name' => 'John Doe',
            'ship_add1' => 'Dhaka',
            'ship_city' => 'Dhaka',
            'ship_country' => 'Bangladesh',

            'shipping_method' => 'NO',
            'product_name' => 'Test Product',
            'product_category' => 'General',
            'product_profile' => 'general',
        ];


        $paymentResponse = SslCommerz::makePayment($post_data);

        // ✅ Send the form/html directly so browser auto-redirects to SSLCommerz
        // dd($paymentResponse->redirectGatewayURL());

        return redirect()->away($paymentResponse->redirectGatewayURL());
    }


    public function success(Request $request)
    {
        $validated = SslCommerz::orderValidate($request->all(), $request->tran_id, $request->amount, $request->currency);

        if ($validated) {
            Payment::create([
                'tran_id' => $request->tran_id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => 'SUCCESS',
                'card_type' => $request->card_type ?? null,
                'card_no' => $request->card_no ?? null,
            ]);

            return "✅ Payment Successful! Transaction ID: " . $request->tran_id;
        }

        return "⚠️ Validation Failed!";
    }

    public function fail(Request $request)
    {
        Payment::create([
            'tran_id' => $request->tran_id ?? uniqid(),
            'amount' => $request->amount ?? 0,
            'currency' => $request->currency ?? 'BDT',
            'status' => 'FAILED',
        ]);

        return "❌ Payment Failed!";
    }

    public function cancel(Request $request)
    {
        Payment::create([
            'tran_id' => $request->tran_id ?? uniqid(),
            'amount' => $request->amount ?? 0,
            'currency' => $request->currency ?? 'BDT',
            'status' => 'CANCELLED',
        ]);

        return "⚠️ Payment Cancelled!";
    }

    public function ipn(Request $request)
    {
        return "📩 IPN Received.";
    }

}
