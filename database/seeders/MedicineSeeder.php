<?php

namespace Database\Seeders;
use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Medicine::truncate();

        $filePath = 'storage/app/myfile/Medicines.json';
        $fileContact = file_get_contents($filePath);
        $medicines = json_decode($fileContact,true);
       // $json = Storage::get("C:\Users\ASUS\Downloads\Medicines.json");
       // $medicines = json_decode($json);

        foreach ($medicines as $medicine) {
            Medicine::create([
                "Classification_id" => $medicine['Classification_id'],
                "Scientific_name" => $medicine['Scientific_name'],
                "Commercial_name" => $medicine['Commercial_name'],
                "Manufacturer" => $medicine['Manufacturer'],
                "Available_Quantity" => $medicine['Available_Quantity'],
                "Expiry_date" => $medicine['Expiry_date'],
                "Price" => $medicine['Price']
            ]);
        }
        /*
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
        */
    }
}
