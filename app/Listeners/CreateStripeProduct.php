<?php

namespace App\Listeners;

use Stripe\StripeClient;
use App\Events\ProductCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateStripeProduct
{
    /**
     * Create the event listener.
     */
    private StripeClient $stripe;
    public function __construct()
    {
        $this->stripe =  new StripeClient(config('cashier.secret'));
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event)
    {
        $product = $event->product;
        try {
           $stripeProduct = $this->stripe->products->create([
                'name' => $product->name,
            ]);
            
            // Create price in Stripe
            $stripePrice = $this->stripe->prices->create([
                'unit_amount' => $product->price * 100, // Price in cents
                'currency' => 'usd', // Adjust as needed
                'product' => $stripeProduct->id,
            ]);
            
            // Save Stripe IDs to the database
            $product->stripe_product_id = $stripeProduct->id;
            $product->stripe_price_id = $stripePrice->id;
            $product->save();
            
        } catch (\Exception $e) {
            \Log::error('Error creating Stripe product: ' . $e->getMessage());
        }
    }
}
