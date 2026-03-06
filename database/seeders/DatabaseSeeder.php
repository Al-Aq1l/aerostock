<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Categories ──────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Electronics',   'slug' => 'electronics',   'icon' => '💻', 'color' => '#2563EB'],
            ['name' => 'Peripherals',   'slug' => 'peripherals',   'icon' => '🖱️', 'color' => '#7C3AED'],
            ['name' => 'Accessories',   'slug' => 'accessories',   'icon' => '🎧', 'color' => '#DB2777'],
            ['name' => 'Networking',    'slug' => 'networking',    'icon' => '📡', 'color' => '#0891B2'],
            ['name' => 'Storage',       'slug' => 'storage',       'icon' => '💾', 'color' => '#059669'],
            ['name' => 'Office',        'slug' => 'office',        'icon' => '🖨️', 'color' => '#D97706'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // ── Products (24) ────────────────────────────────────────────────────
        $products = [
            // Electronics
            ['cat' => 'electronics', 'name' => 'MacBook Pro 14"',        'price' => 32999000, 'cost' => 24500000, 'stock' => 12, 'threshold' => 5,  'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&q=80'],
            ['cat' => 'electronics', 'name' => 'Dell XPS 15',            'price' => 28999000, 'cost' => 21500000, 'stock' => 8,  'threshold' => 4,  'img' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=400&q=80'],
            ['cat' => 'electronics', 'name' => 'iPad Pro 12.9"',         'price' => 17499000, 'cost' => 12500000, 'stock' => 15, 'threshold' => 5,  'img' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400&q=80'],
            ['cat' => 'electronics', 'name' => 'Samsung Galaxy Tab S9',  'price' => 12999000, 'cost' =>  9500000, 'stock' => 7,  'threshold' => 5,  'img' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&q=80'],
            // Peripherals
            ['cat' => 'peripherals', 'name' => 'Apple Magic Keyboard',   'price' =>  1999000, 'cost' =>  1100000, 'stock' => 25, 'threshold' => 10, 'img' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&q=80'],
            ['cat' => 'peripherals', 'name' => 'Logitech MX Master 3S',  'price' =>  1599000, 'cost' =>   850000, 'stock' => 18, 'threshold' => 10, 'img' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&q=80'],
            ['cat' => 'peripherals', 'name' => 'Dell 27" 4K Monitor',    'price' =>  9499000, 'cost' =>  6500000, 'stock' => 6,  'threshold' => 5,  'img' => 'https://images.unsplash.com/photo-1555487505-8603a1a69755?w=400&q=80'],
            ['cat' => 'peripherals', 'name' => 'LG UltraWide 34"',       'price' => 13499000, 'cost' =>  9500000, 'stock' => 4,  'threshold' => 5,  'img' => 'https://images.unsplash.com/photo-1616763355548-1b606f439f86?w=400&q=80'],
            // Accessories
            ['cat' => 'accessories', 'name' => 'AirPods Pro 2',          'price' =>  3999000, 'cost' =>  2500000, 'stock' => 30, 'threshold' => 10, 'img' => 'https://images-cdn.ubuy.co.id/693853a71d89f11303064452-apple-airpods-pro-1st-generation-with.jpg'],
            ['cat' => 'accessories', 'name' => 'Sony WH-1000XM5',        'price' =>  5499000, 'cost' =>  3500000, 'stock' => 14, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400&q=80'],
            ['cat' => 'accessories', 'name' => 'USB-C Hub 7-in-1',       'price' =>   449000, 'cost' =>   180000, 'stock' => 40, 'threshold' => 15, 'img' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400&q=80'],
            ['cat' => 'accessories', 'name' => 'Laptop Stand Pro',        'price' =>   349000, 'cost' =>   150000, 'stock' => 3,  'threshold' => 10, 'img' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=400&q=80'],
            // Networking
            ['cat' => 'networking',  'name' => 'ASUS WiFi 6E Router',    'price' =>  4799000, 'cost' =>  3000000, 'stock' => 10, 'threshold' => 4,  'img' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80'],
            ['cat' => 'networking',  'name' => 'TP-Link Mesh System',    'price' =>  3199000, 'cost' =>  2000000, 'stock' => 7,  'threshold' => 4,  'img' => 'https://images.unsplash.com/photo-1606229365485-93a3b8ee0385?w=400&q=80'],
            ['cat' => 'networking',  'name' => 'Netgear 8-Port Switch',   'price' =>  1249000, 'cost' =>   650000, 'stock' => 20, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=400&q=80'],
            ['cat' => 'networking',  'name' => 'Ubiquiti UniFi AP',      'price' =>  2799000, 'cost' =>  1700000, 'stock' => 9,  'threshold' => 4,  'img' => 'https://images.unsplash.com/photo-1595078475328-1ab05d0a6a0e?w=400&q=80'],
            // Storage
            ['cat' => 'storage',     'name' => 'Samsung 2TB NVMe SSD',   'price' =>  2499000, 'cost' =>  1500000, 'stock' => 22, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=400&q=80'],
            ['cat' => 'storage',     'name' => 'WD 4TB External HDD',    'price' =>  1899000, 'cost' =>  1100000, 'stock' => 18, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=400&q=80'],
            ['cat' => 'storage',     'name' => 'SanDisk 512GB Flash',    'price' =>   299000, 'cost' =>   130000, 'stock' => 50, 'threshold' => 20, 'img' => 'https://www.sandisk.com/content/dam/store/en-us/assets/products/usb-flash-drives/ultra-usb-3-0/gallery/ultra-usb-3-0-angle-angle-up-open.png.thumb.1280.1280.png'],
            ['cat' => 'storage',     'name' => 'Crucial 32GB DDR5 RAM',  'price' =>  1399000, 'cost' =>   800000, 'stock' => 15, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=400&q=80'],
            // Office
            ['cat' => 'office',      'name' => 'HP LaserJet Pro M404',   'price' =>  5499000, 'cost' =>  3800000, 'stock' => 5,  'threshold' => 3,  'img' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=400&q=80'],
            ['cat' => 'office',      'name' => 'Canon PIXMA TS9120',     'price' =>  2399000, 'cost' =>  1450000, 'stock' => 2,  'threshold' => 5,  'img' => 'https://i.pcmag.com/imagery/reviews/00574smgoMU5YSD1VYgbTCU-11..v1569476157.jpg'],
            ['cat' => 'office',      'name' => 'Webcam 4K Pro',          'price' =>  2099000, 'cost' =>  1050000, 'stock' => 16, 'threshold' => 8,  'img' => 'https://images.unsplash.com/photo-1587740908075-9e245070dfaa?w=400&q=80'],
            ['cat' => 'office',      'name' => 'Ergonomic Office Chair', 'price' =>  7199000, 'cost' =>  4600000, 'stock' => 8,  'threshold' => 3,  'img' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&q=80'],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $product = Product::create([
                'category_id' => $catModels[$p['cat']]->id,
                'name'        => $p['name'],
                'sku'         => 'SKU-' . strtoupper(Str::random(6)),
                'price'       => $p['price'],
                'cost'        => $p['cost'],
                'image_url'   => $p['img'],
                'description' => 'Premium ' . $p['name'] . ' — top quality, in stock now.',
            ]);

            Inventory::create([
                'product_id'          => $product->id,
                'quantity'            => $p['stock'],
                'low_stock_threshold' => $p['threshold'],
                'last_restocked_at'   => now()->subDays(rand(1, 30)),
            ]);

            $productModels[] = $product;
        }

        // ── Sales history (30 days) ──────────────────────────────────────────
        for ($day = 30; $day >= 1; $day--) {
            $numSales = rand(3, 10);
            for ($s = 0; $s < $numSales; $s++) {
                $items    = collect($productModels)->random(rand(1, 4));
                $subtotal = 0;
                $lineItems = [];
                foreach ($items as $prod) {
                    $qty     = rand(1, 3);
                    $lineSub = $prod->price * $qty;
                    $subtotal += $lineSub;
                    $lineItems[] = ['product' => $prod, 'qty' => $qty, 'sub' => $lineSub];
                }
                $tax   = round($subtotal * 0.10, 2);
                $total = $subtotal + $tax;

                $sale = Sale::create([
                    'reference'      => 'INV-' . strtoupper(Str::random(8)),
                    'subtotal'       => $subtotal,
                    'tax'            => $tax,
                    'total'          => $total,
                    'payment_method' => collect(['cash','card','ewallet'])->random(),
                    'status'         => 'completed',
                    'created_at'     => now()->subDays($day)->addHours(rand(8, 21))->addMinutes(rand(0, 59)),
                    'updated_at'     => now()->subDays($day),
                ]);

                foreach ($lineItems as $li) {
                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $li['product']->id,
                        'quantity'   => $li['qty'],
                        'unit_price' => $li['product']->price,
                        'subtotal'   => $li['sub'],
                    ]);
                }
            }
        }
    }
}
