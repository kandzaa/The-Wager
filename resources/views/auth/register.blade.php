<x-guest-layout>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Inter', sans-serif; background: #0a0a0a; }
  .float-input::placeholder { color: transparent; }
  .float-input:focus ~ .float-label,
  .float-input:not(:placeholder-shown) ~ .float-label {
    top: 0.4rem; font-size: 0.6rem; letter-spacing: 0.08em; color: #10b981;
  }
  @keyframes in { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
  .in { animation: in 0.5s cubic-bezier(0.16,1,0.3,1) both; }
  .d1{animation-delay:.05s}.d2{animation-delay:.1s}.d3{animation-delay:.15s}.d4{animation-delay:.2s}.d5{animation-delay:.25s}.d6{animation-delay:.3s}.d7{animation-delay:.35s}
  .seg { height: 2px; flex:1; border-radius:2px; background:rgba(255,255,255,0.06); transition:background .3s; }
</style>

<div class="min-h-screen bg-[#0a0a0a] flex items-center justify-center px-4">
  <div class="w-full max-w-[380px] py-8">

    {{-- Logo --}}
    <div class="in d1 flex items-center gap-2 mb-12">
      <div class="w-2 h-2 rounded-full bg-emerald-400" style="box-shadow:0 0 8px #10b981"></div>
      <span class="text-white text-sm font-medium tracking-wide">WagerApp</span>
    </div>

    {{-- Heading --}}
    <div class="in d2 mb-8">
      <h1 class="text-white text-3xl font-light tracking-tight">Create account</h1>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
      @csrf

      {{-- Name --}}
      <div class="in d3 relative">
        <input id="name" type="text" name="name" value="{{ old('name') }}"
          placeholder="name" required autofocus autocomplete="name"
          class="float-input w-full h-14 pt-5 pb-2 px-4 bg-white/[0.04] border text-white text-sm rounded-lg outline-none transition-all
                 {{ $errors->has('name') ? 'border-red-500/50' : 'border-white/[0.08] focus:border-emerald-500/50' }}" />
        <label for="name" class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-white/30 transition-all duration-150">
          Name
        </label>
        @if($errors->has('name'))
          <p class="mt-1.5 text-xs text-red-400">{{ $errors->first('name') }}</p>
        @endif
      </div>

      {{-- Email --}}
      <div class="in d4 relative">
        <input id="email" type="email" name="email" value="{{ old('email') }}"
          placeholder="email" required autocomplete="username"
          class="float-input w-full h-14 pt-5 pb-2 px-4 bg-white/[0.04] border text-white text-sm rounded-lg outline-none transition-all
                 {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/[0.08] focus:border-emerald-500/50' }}" />
        <label for="email" class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-white/30 transition-all duration-150">
          Email
        </label>
        @if($errors->has('email'))
          <p class="mt-1.5 text-xs text-red-400">{{ $errors->first('email') }}</p>
        @endif
      </div>

      {{-- Password --}}
      <div class="in d5 relative">
        <input id="password" type="password" name="password"
          placeholder="password" required autocomplete="new-password"
          oninput="updateStrength(this.value)"
          class="float-input w-full h-14 pt-5 pb-2 px-4 bg-white/[0.04] border text-white text-sm rounded-lg outline-none transition-all
                 {{ $errors->has('password') ? 'border-red-500/50' : 'border-white/[0.08] focus:border-emerald-500/50' }}" />
        <label for="password" class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-white/30 transition-all duration-150">
          Password
        </label>
        @if($errors->has('password'))
          <p class="mt-1.5 text-xs text-red-400">{{ $errors->first('password') }}</p>
        @endif
        <div class="flex gap-1 mt-1.5">
          <div id="s1" class="seg"></div><div id="s2" class="seg"></div>
          <div id="s3" class="seg"></div><div id="s4" class="seg"></div>
        </div>
      </div>

      {{-- Confirm Password --}}
      <div class="in d6 relative">
        <input id="password_confirmation" type="password" name="password_confirmation"
          placeholder="confirm" required autocomplete="new-password"
          class="float-input w-full h-14 pt-5 pb-2 px-4 bg-white/[0.04] border text-white text-sm rounded-lg outline-none transition-all
                 {{ $errors->has('password_confirmation') ? 'border-red-500/50' : 'border-white/[0.08] focus:border-emerald-500/50' }}" />
        <label for="password_confirmation" class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-white/30 transition-all duration-150">
          Confirm password
        </label>
        @if($errors->has('password_confirmation'))
          <p class="mt-1.5 text-xs text-red-400">{{ $errors->first('password_confirmation') }}</p>
        @endif
      </div>

      {{-- Submit --}}
      <div class="in d7 pt-2">
        <button type="submit"
          class="w-full h-12 bg-emerald-500 hover:bg-emerald-400 text-black text-sm font-medium rounded-lg
                 transition-all duration-200 hover:-translate-y-px hover:shadow-[0_4px_20px_rgba(16,185,129,0.25)]
                 active:translate-y-0">
          Create account
        </button>
      </div>
    </form>

    <p class="in d7 mt-6 text-xs text-white/25">
      Already registered?
      <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 transition-colors ml-1">Sign in</a>
    </p>

  </div>
</div>

<script>
function updateStrength(v) {
  const c = ['#ef4444','#f97316','#3b82f6','#10b981'];
  let s = 0;
  if (v.length >= 8) s++;
  if (/[A-Z]/.test(v)) s++;
  if (/[0-9]/.test(v)) s++;
  if (/[^A-Za-z0-9]/.test(v)) s++;
  ['s1','s2','s3','s4'].forEach((id,i) => {
    document.getElementById(id).style.background = i < s ? c[Math.min(s-1,3)] : 'rgba(255,255,255,0.06)';
  });
}
</script>
</x-guest-layout>