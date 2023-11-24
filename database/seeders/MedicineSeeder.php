<?php

namespace Database\Seeders;
use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medicine::truncate();
        Medicine::create([
            'Classification_id'=>2,
            'Scientific_name'=>'Furosemide',
            'Commercial_name'=>'Uni lasix',
            'Manufacturer'=>'Unipharma',
            'Available_Quantity'=>50,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>2,
            'Scientific_name'=>'Torespot',
            'Commercial_name'=>'Torsemide',
            'Manufacturer'=>'Unipharma',
            'Available_Quantity'=>60,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>1,
            'Scientific_name'=>'Carvidomet',
            'Commercial_name'=>'Trimetazidine hydrochloride',
            'Manufacturer'=>'Barakat pharmaceutical',
            'Available_Quantity'=>40,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>2,
            'Scientific_name'=>'Alctone',
            'Commercial_name'=>'Spironolactone',
            'Manufacturer'=>'Medico labs',
            'Available_Quantity'=>30,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>1,
            'Scientific_name'=>'Anti-Angina',
            'Commercial_name'=>'Ranolazine',
            'Manufacturer'=>'Ibn al haytham',
            'Available_Quantity'=>30,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>1,
            'Scientific_name'=>'Amlodipine oubari',
            'Commercial_name'=>'Amlodipine',
            'Manufacturer'=>'Oubari',
            'Available_Quantity'=>30,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>1,
            'Scientific_name'=>'Normotic',
            'Commercial_name'=>'Atenolol',
            'Manufacturer'=>'Barakat pharmaceutical',
            'Available_Quantity'=>30,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
        Medicine::create([
            'Classification_id'=>2,
            'Scientific_name'=>'Ameloride and hydrochlorothiazide',
            'Commercial_name'=>'Moduretic',
            'Manufacturer'=>'algoithm sal',
            'Available_Quantity'=>30,
            'Expiry_date'=>'12/11/2025',
            'Price'=>50,
        ]);
    }
}
