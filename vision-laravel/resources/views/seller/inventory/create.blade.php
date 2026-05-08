<x-layouts.dashboard title="رفع بطاقات جديدة" dashboardType="seller">
    <x-ui.page-header title="توليد بطاقات" description="إنشاء بطاقات جديدة للمخزون" />
    <x-ui.panel>
        <form method="POST" action="{{ route('seller.inventory.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text">الباقة</span>
                <select name="package_id" class="select select-bordered" required>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="label-text">الكمية</span>
                <input type="number" name="quantity" min="1" max="100" class="input input-bordered" required />
            </label>
            <label class="form-control">
                <span class="label-text">السعر (اختياري)</span>
                <input type="number" step="0.01" name="price" class="input input-bordered" />
            </label>
            <label class="form-control">
                <span class="label-text">تاريخ الانتهاء (اختياري)</span>
                <input type="date" name="expires_at" class="input input-bordered" />
            </label>
            <div class="md:col-span-2"><button class="btn btn-primary">تنفيذ الرفع</button></div>
        </form>
    </x-ui.panel>

    <x-ui.panel class="mt-6">
        <h2 class="mb-2 text-lg font-semibold">استيراد CSV</h2>
        <p class="mb-4 text-sm opacity-80">كل سطر: <code class="text-xs">code</code> أو <code class="text-xs">code,serial</code>.</p>
        <p class="mb-4 rounded-xl bg-amber-500/10 px-3 py-2 text-xs leading-6 text-amber-900">ملفات Excel (.xlsx) ستُدعم لاحقاً؛ يُرجى تصدير الجدول كـ CSV من Excel أو Google Sheets حالياً.</p>
        <form method="POST" action="{{ route('seller.inventory.import-csv') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text">الباقة</span>
                <select name="package_id" class="select select-bordered" required>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="label-text">ملف CSV</span>
                <input type="file" name="file" accept=".csv,.txt" class="file-input file-input-bordered w-full" required />
            </label>
            <div class="md:col-span-2"><button type="submit" class="btn btn-outline btn-primary">رفع CSV</button></div>
        </form>
        @if (session('import_errors'))
            @php($errs = session('import_errors'))
            <div class="mt-4 rounded-xl border border-error/30 bg-error/5 p-3">
                <p class="mb-2 text-sm font-semibold text-error">أخطاء في الملف (عرض حتى 80 سطراً)</p>
                <ul class="max-h-64 list-inside list-disc overflow-y-auto text-xs text-error">
                    @foreach (array_slice($errs, 0, 80) as $err)
                        <li>سطر {{ $err['line'] ?? '?' }}: {{ $err['message'] ?? '' }}</li>
                    @endforeach
                </ul>
                @if (count($errs) > 80)
                    <p class="mt-2 text-xs opacity-70">… و{{ count($errs) - 80 }} سطراً إضافياً</p>
                @endif
            </div>
        @endif
    </x-ui.panel>
</x-layouts.dashboard>
