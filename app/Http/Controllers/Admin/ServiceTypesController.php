<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class ServiceTypesController extends Controller
{
    public function index()
    {
        $serviceTypes = $this->getServiceTypes();
        return response()->json($serviceTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'icon' => 'required|string|max:10',
        ]);

        try {
            $serviceTypes = $this->getServiceTypes();
            
            // Yeni tür ekle
            $newType = [
                'id' => uniqid(),
                'name' => $request->name,
                'icon' => $request->icon,
                'color' => $this->generateRandomColor(),
                'created_at' => now()->toISOString()
            ];
            
            $serviceTypes[] = $newType;
            
            // Ayarları güncelle
            Setting::updateOrCreate(
                ['key' => 'service_types'],
                ['value' => json_encode($serviceTypes)]
            );

            return response()->json([
                'success' => true,
                'message' => 'Hizmet türü başarıyla eklendi',
                'type' => $newType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $serviceTypes = $this->getServiceTypes();
            
            // ID'ye göre türü bul ve kaldır
            $serviceTypes = array_filter($serviceTypes, function($type) use ($id) {
                return $type['id'] !== $id;
            });
            
            // Ayarları güncelle
            Setting::updateOrCreate(
                ['key' => 'service_types'],
                ['value' => json_encode(array_values($serviceTypes))]
            );

            return response()->json([
                'success' => true,
                'message' => 'Hizmet türü başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getServiceTypes()
    {
        $setting = Setting::where('key', 'service_types')->first();
        
        if ($setting && $setting->value) {
            return json_decode($setting->value, true) ?: [];
        }

        // Varsayılan türler
        return [
            [
                'id' => 'domain',
                'name' => 'Domain',
                'icon' => '🌐',
                'color' => '#3B82F6',
                'created_at' => now()->toISOString()
            ],
            [
                'id' => 'hosting',
                'name' => 'Hosting',
                'icon' => '🖥️',
                'color' => '#10B981',
                'created_at' => now()->toISOString()
            ],
            [
                'id' => 'ssl',
                'name' => 'SSL',
                'icon' => '🔒',
                'color' => '#8B5CF6',
                'created_at' => now()->toISOString()
            ],
            [
                'id' => 'email',
                'name' => 'E-mail',
                'icon' => '📧',
                'color' => '#06B6D4',
                'created_at' => now()->toISOString()
            ],
            [
                'id' => 'other',
                'name' => 'Diğer',
                'icon' => '📦',
                'color' => '#6B7280',
                'created_at' => now()->toISOString()
            ]
        ];
    }

    private function generateRandomColor()
    {
        $colors = [
            '#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16',
            '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#3B82F6',
            '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899',
            '#F43F5E', '#84CC16', '#22C55E', '#10B981', '#14B8A6'
        ];
        
        return $colors[array_rand($colors)];
    }
}
