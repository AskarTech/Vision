<x-layouts.dashboard title="طلب سحب" description="إرسال طلب تحويل إلى حسابك البنكي" dashboardType="seller">

    <x-ui.panel title="بيانات الطلب">
        <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text vision-form-label">المبلغ</span>
                <input type="number" step="0.01" name="amount" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">البنك</span>
                <select name="bank_id" class="select select-bordered vision-form-input" required>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">رقم الحساب</span>
                <input name="account_number" class="input input-bordered vision-form-input" required />
            </label>
            <label class="form-control">
                <span class="label-text vision-form-label">اسم المستفيد</span>
                <input name="receiver_name" class="input input-bordered vision-form-input" required />
            </label>
            <div class="md:col-span-2 flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">إرسال الطلب</button>
                <a href="{{ route('seller.withdrawals.index') }}" class="vision-outline-btn">إلغاء</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
