<x-layouts.dashboard title="تعديل شبكة" dashboardType="seller">
    <x-ui.page-header title="تعديل الشبكة" description="{{ $network->name }}" />

    <x-ui.panel class="rounded-[1.5rem]">
        <form method="POST" action="{{ route('seller.networks.update', $network) }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PATCH')
            <label class="form-control md:col-span-2">
                <span class="label-text">اسم الشبكة</span>
                <input name="name" value="{{ old('name', $network->name) }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">المعرّف (slug)</span>
                <input name="slug" value="{{ old('slug', $network->slug) }}" class="input input-bordered rounded-xl" required />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">رمز المزود</span>
                <input name="provider_code" value="{{ old('provider_code', $network->provider_code) }}" class="input input-bordered rounded-xl" />
            </label>
            <label class="form-control md:col-span-2">
                <span class="label-text">الحالة</span>
                <select name="status" class="select select-bordered rounded-xl">
                    @foreach (['active', 'disabled'] as $st)
                        <option value="{{ $st }}" @selected(old('status', $network->status) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </label>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary rounded-xl">تحديث</button>
                <a href="{{ route('seller.networks.index') }}" class="btn btn-ghost rounded-xl">رجوع</a>
            </div>
        </form>
    </x-ui.panel>
</x-layouts.dashboard>
