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
  .d1{animation-delay:.05s}.d2{animation-delay:.1s}.d3{animation-delay:.15s}.d4{animation-delay:.2s}.d5{animation-delay:.25s}
</style>

<div class="min-h-screen bg-[#0a0a0a] flex items-center justify-center px-4">
  <div class="w-full max-w-[380px]">

    {{-- Logo --}}
    <div class="in d1 flex items-center gap-2 mb-12">
      <div class="w-2 h-2 rounded-full bg-emerald-400" style="box-shadow:0 0 8px #10b981"></div>
      <span class="text-white text-sm font-medium tracking-wide">WagerApp</span>
    </div>

    {{-- Heading --}}
    <div class="in d2 mb-8">
      <h1 class="text-white text-3xl font-light tracking-tight">Welcome back</h1>
    </div>

    @if(session('status'))
      <div class="in d2 mb-6 text-emerald-400 text-sm">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      {{-- Email --}}
      <div class="in d3 relative">
        <input id="email" type="email" name="email" value="{{ old('email') }}"
          placeholder="email" required autofocus autocomplete="username"
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
      <div class="in d4 relative">
        <input id="password" type="password" name="password"
          placeholder="password" required autocomplete="current-password"
          class="float-input w-full h-14 pt-5 pb-2 px-4 bg-white/[0.04] border text-white text-sm rounded-lg outline-none transition-all
                 {{ $errors->has('password') ? 'border-red-500/50' : 'border-white/[0.08] focus:border-emerald-500/50' }}" />
        <label for="password" class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-white/30 transition-all duration-150">
          Password
        </label>
        @if($errors->has('password'))
          <p class="mt-1.5 text-xs text-red-400">{{ $errors->first('password') }}</p>
        @endif
      </div>

      {{-- Remember --}}
      <div class="in d4 flex items-center gap-2 pt-1">
        <input id="remember_me" type="checkbox" name="remember"
          class="w-3.5 h-3.5 rounded border-white/10 bg-white/[0.04] accent-emerald-400 cursor-pointer" />
        <label for="remember_me" class="text-xs text-white/30 cursor-pointer">Remember me</label>
      </div>

      {{-- Submit --}}
      <div class="in d5 pt-2">
        <button type="submit"
          class="w-full h-12 bg-emerald-500 hover:bg-emerald-400 text-black text-sm font-medium rounded-lg
                 transition-all duration-200 hover:-translate-y-px hover:shadow-[0_4px_20px_rgba(16,185,129,0.25)]
                 active:translate-y-0">
          Sign in
        </button>
      </div>
    </form>

    <p class="in d5 mt-6 text-xs text-white/25">
      No account?
      <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 transition-colors ml-1">Register</a>
    </p>

  </div>
</div>
</x-guest-layout>