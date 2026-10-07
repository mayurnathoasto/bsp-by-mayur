<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Air Rail Road transportation & Auto parts',
            'Alcoholic Beverages Tobacco & products',
            'Bags Baggages & Suitcases',
            'Books , Magazines',
            'Builders Hardware , Construction Material & Equipment',
            'Carpets , Mats, Rugs, Floorings',
            'Chemicals & Allied Products',
            'Civil industrial explosives',
            'Computer Hardware and Software',
            'Cosmetics Toiletries & Hygiene Products',
            'Electrical Goods & Components',
            'Electronics & Communication',
            'Elevators & Escalators',
            'Fertilizers Pesticides & Seeds',
            'Fitness equipment & Sports goods',
            'Flowers Plants & products Live Stock Poultry Meat & Animal products',
            'Food processing machinery & equipment',
            'Fuel & Energy resources',
            'Furniture , Interior Decoration & Furnishings',
            'Gems & Jewellery',
            'Glass & Ceramics',
            'Grain Fruits & Dry Fruits etc',
            'Handicrafts Decorations & Gifts',
            'Heating , Air conditioners & Ventilation',
            'Hospital & Medical',
            'Hotels Motels & Restaurants',
            'Household & General Merchandise',
            'Industrial Goods & Supplies',
            'Industrial Machinery & Plants',
            'Jute – Raw materials , Processed & Related Machinery',
            'Leather & leather Products',
            'Marine Equipment & Machinery',
            'Metal & Metal Products',
            'Metallic Elements',
            'Music Movies & Entertainment',
            'Non Metallic Elements',
            'Office Equipment Stationary and Supples',
            'Optical & Photographic Equipment',
            'Ores & Minerals',
            'Paper & Paper Products',
            'Petroleum Oil & Related Products',
            'Pets & Pet supplies',
            'Pharmaceuticals',
            'Pipes Hoses Tubes & Fittings',
            'Plastic Products',
            'Pollution control products',
            'Power Plants',
            'Printing & Packaging Products',
            'Processed Food Beverages & by products',
            'Rubber & Related Products',
            'Safety & Security items',
            'Textile Garment Fashion Accessories & Footwear',
            'Tools & Instruments',
            'Watches & Clocks',
            'Wood – Raw material , Processed & Related Machinery',
        ];

        foreach ($names as $idx => $name) {
            $slugBase = Str::slug($name);
            $slug = $slugBase;
            $n = 1;

            // Ensure unique slug if any collisions
            while (Category::where('slug', $slug)->exists()) {
                $n++;
                $slug = $slugBase . '-' . $n;
            }

            Category::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => $slug,
                    'is_active' => true,
                    'sort_order' => $idx + 1,
                ]
            );
        }
    }
}
