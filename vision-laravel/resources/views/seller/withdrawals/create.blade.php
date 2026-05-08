<x-layouts.dashboard title="طلب سحب جديد" dashboardType="seller">
    <x-ui.page-header title="طلب سحب" />
    <x-ui.panel>
        <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="form-control">
                <span class="label-text">المبلغ</span>
                <input type="number" step="0.01" name="amount" class="input input-bordered" required />
            </label>
            <label class="form-control">
                <span class="label-text">البنك</span>
                <select name="bank_id" class="select select-bordered" required>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="label-text">رقم الحساب</span>
                <input name="account_number" class="input input-bordered" required />
            </label>
            <label class="form-control">
                <span class="label-text">اسم المستفيد</span>
                <input name="receiver_name" class="input input-bordered" required />
            </label>
            <div class="md:col-span-2"><button class="btn btn-primary">إرسال الطلب</button></div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
