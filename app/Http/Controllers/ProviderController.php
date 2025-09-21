<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::withCount('services')->latest()->paginate(15);
        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Build allowed provider types dynamically from settings
        $serviceTypesJson = Setting::get('service_types');
        $serviceTypesArr = $serviceTypesJson ? json_decode($serviceTypesJson, true) : [];
        if (!is_array($serviceTypesArr)) { $serviceTypesArr = []; }
        $allowedTypeIds = array_values(array_filter(array_map(function ($item) {
            if (is_array($item)) {
                if (isset($item['id'])) { return $item['id']; }
                if (isset($item['value'])) { return $item['value']; }
            }
            return is_string($item) ? $item : null;
        }, $serviceTypesArr)));
        if (empty($allowedTypeIds)) { $allowedTypeIds = ['domain','hosting','ssl','email','other']; }
        $typesRule = 'in:' . implode(',', $allowedTypeIds);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'types' => ['required', 'array', 'min:1'],
            'types.*' => [$typesRule],
            'website' => ['nullable', 'url', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
        ], [
            'name.required' => 'Sağlayıcı adı zorunludur.',
            'name.min' => 'Sağlayıcı adı en az 2 karakter olmalıdır.',
            'types.required' => 'En az bir hizmet türü seçilmelidir.',
            'types.array' => 'Hizmet türleri dizi olmalıdır.',
            'types.min' => 'En az bir hizmet türü seçilmelidir.',
            'types.*.in' => 'Geçersiz hizmet türü.',
            'website.url' => 'Geçerli bir website adresi giriniz.',
            'support_email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
        ]);

        try {
            $provider = Provider::create([
                'name' => $validated['name'],
                'type' => $validated['types'],
                'contact_info' => array_filter([
                    'website' => $validated['website'] ?? null,
                    'support_email' => $validated['support_email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                ]),
                // Eski kolonları da doldur (geriye uyumluluk için)
                'email' => $validated['support_email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'website' => $validated['website'] ?? null,
            ]);

            \Log::info('Provider created successfully', ['id' => $provider->id, 'name' => $provider->name]);
            return redirect()->route('providers.index')->with('status', 'Sağlayıcı oluşturuldu');
        } catch (\Exception $e) {
            \Log::error('Provider creation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Sağlayıcı oluşturulamadı: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        $contact = $provider->contact_info ?? [];
        $customers = Customer::orderBy('name')->get();
        $services = Service::with('customer')->where('provider_id', $provider->id)->latest('id')->get();
        return view('providers.edit', compact('provider', 'contact', 'customers', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        // Build allowed provider types dynamically from settings
        $serviceTypesJson = Setting::get('service_types');
        $serviceTypesArr = $serviceTypesJson ? json_decode($serviceTypesJson, true) : [];
        if (!is_array($serviceTypesArr)) { $serviceTypesArr = []; }
        $allowedTypeIds = array_values(array_filter(array_map(function ($item) {
            if (is_array($item)) {
                if (isset($item['id'])) { return $item['id']; }
                if (isset($item['value'])) { return $item['value']; }
            }
            return is_string($item) ? $item : null;
        }, $serviceTypesArr)));
        if (empty($allowedTypeIds)) { $allowedTypeIds = ['domain','hosting','ssl','email','other']; }
        $typesRule = 'in:' . implode(',', $allowedTypeIds);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'types' => ['required', 'array', 'min:1'],
            'types.*' => [$typesRule],
            'website' => ['nullable', 'url', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
        ], [
            'name.required' => 'Sağlayıcı adı zorunludur.',
            'name.min' => 'Sağlayıcı adı en az 2 karakter olmalıdır.',
            'types.required' => 'En az bir hizmet türü seçilmelidir.',
            'types.array' => 'Hizmet türleri dizi olmalıdır.',
            'types.min' => 'En az bir hizmet türü seçilmelidir.',
            'types.*.in' => 'Geçersiz hizmet türü.',
            'website.url' => 'Geçerli bir website adresi giriniz.',
            'support_email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
        ]);

        $provider->update([
            'name' => $validated['name'],
            'type' => $validated['types'],
            'contact_info' => array_filter([
                'website' => $validated['website'] ?? null,
                'support_email' => $validated['support_email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]),
            // Eski kolonları da güncelle (geriye uyumluluk için)
            'email' => $validated['support_email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'website' => $validated['website'] ?? null,
        ]);

        return redirect()->route('providers.index')->with('status', 'Sağlayıcı güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')->with('status', 'Sağlayıcı silindi');
    }
}
