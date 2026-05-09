<x-layouts.dashboard title="توليد بطاقات" description="إنشاء بطاقات جديدة أو استيراد CSV للمخزون" dashboardType="seller">

    <x-ui.panel title="رفع تلقائي (كمية)" description="يتم إنشاء الأكواد من النظام">
        <form method="POST" action="{{ route('seller.inventory.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الباقة</span>
                <select name="package_id" class="select select-bordered vision-form-input" required>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">الكمية</span>
                <input type="number" name="quantity" min="1" max="100" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">السعر (اختياري)</span>
                <input type="number" step="0.01" name="price" class="input input-bordered vision-form-input" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">تاريخ الانتهاء (اختياري)</span>
                <input type="date" name="expires_at" class="input input-bordered vision-form-input" />
            </label>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary">تنفيذ الرفع</button>
            </div>
        </form>
    </x-ui.panel>

    <x-ui.panel title="استيراد CSV" description="كل سطر: code أو code,serial" class="mt-8">
        <p class="mb-4 rounded-[10px] border border-amber-200 bg-amber-50 px-3 py-2 text-xs leading-relaxed text-amber-950">ملفات Excel (.xlsx) ستُدعم لاحقاً؛ صدّر الجدول كـ CSV من Excel أو Google Sheets حالياً.</p>
        <form method="POST" action="{{ route('seller.inventory.import-csv') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control md:col-span-2">
                <span class="label-text vision-form-label">الباقة</span>
                <select name="package_id" class="select select-bordered vision-form-input" required>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </label>
            <div class="form-control md:col-span-2">
                <span class="label-text vision-form-label">ملف CSV</span>
                <label class="relative block overflow-hidden rounded-[10px]">
                    <span class="vision-file-upload-box flex min-h-[5rem] flex-col items-center justify-center">
                        <input type="file" name="file" accept=".csv,.txt"
                            class="absolute inset-0 z-10 cursor-pointer opacity-0" required />
                        <span class="pointer-events-none text-sm">اضغط لاختيار ملف CSV أو اسحبه هنا</span>
                    </span>
                </label>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-outline border-[#e2e8f0] text-slate-700 hover:bg-slate-50">رفع CSV</button>
            </div>
        </form>
        @if (session('import_errors'))
            @php($errs = session('import_errors'))
            <div class="mt-6 rounded-[10px] border border-red-200 bg-red-50 p-4">
                <p class="mb-2 text-sm font-bold text-red-900">أخطاء في الملف (عرض حتى 80 سطراً)</p>
                <ul class="max-h-64 list-inside list-disc overflow-y-auto text-xs text-red-800">
                    @foreach (array_slice($errs, 0, 80) as $err)
                        <li>سطر {{ $err['line'] ?? '?' }}: {{ $err['message'] ?? '' }}</li>
                    @endforeach
                </ul>
                @if (count($errs) > 80)
                    <p class="mt-2 text-xs text-red-700">… و{{ count($errs) - 80 }} سطراً إضافياً</p>
                @endif
            </div>
        @endif
    </x-ui.panel>
</x-layouts.dashboard>
