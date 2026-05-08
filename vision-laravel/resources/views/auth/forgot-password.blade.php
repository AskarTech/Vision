<x-layouts.auth title="استعادة كلمة المرور" description="أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور.">
    <div class="rounded-4xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur sm:p-8">
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <input name="email" value="{{ old('email') }}" class="input input-bordered w-full" placeholder="البريد الإلكتروني" required />
            <button type="submit" class="btn btn-primary w-full">إرسال رابط الاستعادة</button>
        </form>
    </div>
</x-layouts.auth>
