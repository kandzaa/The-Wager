<x-guest-layout>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
  .font-playfair { font-family: 'Playfair Display', serif; }
  .font-outfit   { font-family: 'Outfit', sans-serif; }

  /* floating label trick */
  .float-input::placeholder { color: transparent; }
  .float-input:focus ~ .float-label,
  .float-input:not(:placeholder-shown) ~ .float-label {
    top: 0.45rem;
    font-size: 0.6rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #00ff87;
  }

  /* scanlines on left panel */
  .scanlines::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,0.18) 2px,rgba(0,0,0,0.18) 4px);
    pointer-events: none;
    opacity: 0.5;
  }

  /* glow pulse */
  @keyframes glow-pulse {
    0%,100% { box-shadow: 0 0 8px #00ff87, 0 0 16px rgba(0,255,135,0.3); }
    50%      { box-shadow: 0 0 18px #00ff87, 0 0 36px rgba(0,255,135,0.4); }
  }
  .glow-dot { animation: glow-pulse 2.4s ease-in-out infinite; }

  /* btn shine overlay */
  .btn-shine::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, rgba(255,255,255,0.18) 0%, transparent 55%);
    pointer-events: none;
    border-radius: inherit;
  }

  /* stagger */
  @keyframes up-in {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .up-in   { animation: up-in 0.6s cubic-bezier(0.16,1,0.3,1) both; }
  .d-1     { animation-delay: 0.05s; }
  .d-2     { animation-delay: 0.12s; }
  .d-3     { animation-delay: 0.18s; }
  .d-4     { animation-delay: 0.24s; }
  .d-5     { animation-delay: 0.30s; }
</style>

<div class="font-outfit min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#050608] text-[#e8eaed]">

  {{-- ── LEFT PANEL ── --}}
  <div class="scanlines relative hidden lg:flex flex-col justify-between p-12 bg-[#0c0e12] border-r border-white/[0.06] overflow-hidden">

    {{-- ambient glow --}}
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-0 left-0 w-[500px] h-[500px] rounded-full"
           style="background:radial-gradient(circle,rgba(0,255,135,0.07) 0%,transparent 65%)"></div>
      <div class="absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full"
           style="background:radial-gradient(circle,rgba(0,200,100,0.04) 0%,transparent 65%)"></div>
    </div>

    {{-- logo --}}
    <div class="relative z-10 flex items-center gap-2.5">
      <div class="glow-dot w-2.5 h-2.5 rounded-full bg-[#00ff87]"></div>
      <span class="font-playfair text-[#00ff87] font-bold tracking-wide text-lg">WagerApp</span>
    </div>

    {{-- hero text --}}
    <div class="relative z-10">
      <div class="inline-flex items-center gap-1.5 px-3 py-1.5 mb-6
                  text-[0.6rem] font-semibold tracking-[0.22em] uppercase text-[#00ff87]
                  border border-[#00ff87]/20 rounded-full bg-[#00ff87]/5">
        Members area
      </div>
      <h1 class="font-playfair font-black leading-[0.95] tracking-tight text-[4.5rem] text-white mb-6">
        Place your<br>
        <em class="text-[#00ff87] not-italic">bets.</em>
      </h1>
      <p class="text-sm font-light text-[#4a5060] leading-relaxed max-w-[270px]">
        Compete with friends. Win real stakes.<br>Every wager tells a story.
      </p>
    </div>

    <div class="relative z-10 text-xs text-[#4a5060] tracking-wide">
      © {{ date('Y') }} WagerApp. All rights reserved.
    </div>
  </div>

  {{-- ── RIGHT PANEL ── --}}
  <div class="relative flex items-center justify-center px-6 py-12 bg-[#050608] overflow-hidden">
    <div class="absolute inset-0 pointer-events-none"
         style="background:radial-gradient(ellipse 60% 60% at 50% 50%,rgba(0,255,135,0.03) 0%,transparent 70%)"></div>

    <div class="relative z-10 w-full max-w-[360px]">

      {{-- heading --}}
      <div class="up-in d-1 mb-8">
        <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-[#4a5060] mb-1.5">Welcome back</p>
        <h2 class="font-playfair text-[2.2rem] font-bold tracking-tight text-white">Sign in</h2>
      </div>

      @if (session('status'))
        <div class="up-in d-1 mb-5 px-4 py-2.5 rounded-lg bg-[#00ff87]/[0.06] border border-[#00ff87]/20 text-[0.78rem] text-[#6effc0]">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-3">
        @csrf

        {{-- email --}}
        <div class="up-in d-2 relative">
          <input
            id="email" type="email" name="email"
            value="{{ old('email') }}"
            placeholder="email"
            required autofocus autocomplete="username"
            class="float-input peer w-full h-[52px] pt-5 pb-1.5 px-4
                   bg-white/[0.03] border rounded-xl text-[0.9rem] text-white
                   outline-none transition-all duration-200
                   {{ $errors->has('email')
                        ? 'border-[#ff4d6d]/50 shadow-[0_0_0_3px_rgba(255,77,109,0.08)]'
                        : 'border-white/[0.07] focus:border-[#00ff87]/40 focus:bg-[#00ff87]/[0.02] focus:shadow-[0_0_0_3px_rgba(0,255,135,0.07)]' }}"
          />
          <label for="email"
            class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2
                   text-[0.82rem] text-[#4a5060] transition-all duration-200">
            Email address
          </label>
          @if ($errors->has('email'))
            <p class="mt-1.5 pl-1 text-[0.72rem] text-[#ff4d6d] flex items-center gap-1.5">
              <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-[#ff4d6d] text-white text-[0.55rem] font-bold flex-shrink-0">!</span>
              {{ $errors->first('email') }}
            </p>
          @endif
        </div>

        {{-- password --}}
        <div class="up-in d-3 relative">
          <input
            id="password" type="password" name="password"
            placeholder="password"
            required autocomplete="current-password"
            class="float-input peer w-full h-[52px] pt-5 pb-1.5 px-4
                   bg-white/[0.03] border rounded-xl text-[0.9rem] text-white
                   outline-none transition-all duration-200
                   {{ $errors->has('password')
                        ? 'border-[#ff4d6d]/50 shadow-[0_0_0_3px_rgba(255,77,109,0.08)]'
                        : 'border-white/[0.07] focus:border-[#00ff87]/40 focus:bg-[#00ff87]/[0.02] focus:shadow-[0_0_0_3px_rgba(0,255,135,0.07)]' }}"
          />
          <label for="password"
            class="float-label pointer-events-none absolute left-4 top-1/2 -translate-y-1/2
                   text-[0.82rem] text-[#4a5060] transition-all duration-200">
            Password
          </label>
          @if ($errors->has('password'))
            <p class="mt-1.5 pl-1 text-[0.72rem] text-[#ff4d6d] flex items-center gap-1.5">
              <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-[#ff4d6d] text-white text-[0.55rem] font-bold flex-shrink-0">!</span>
              {{ $errors->first('password') }}
            </p>
          @endif
        </div>

        {{-- remember --}}
        <div class="up-in d-4 flex items-center gap-2.5 pt-1">
          <input id="remember_me" type="checkbox" name="remember"
            class="w-4 h-4 rounded border border-white/10 bg-white/[0.03]
                   accent-[#00ff87] cursor-pointer" />
          <label for="remember_me" class="text-[0.78rem] text-[#4a5060] cursor-pointer select-none">
            Remember me
          </label>
        </div>

        {{-- submit --}}
        <div class="up-in d-5 pt-2">
          <button type="submit"
            class="btn-shine relative w-full h-[50px] flex items-center justify-center gap-2
                   bg-[#00ff87] hover:bg-[#00e87a] text-[#020804] font-semibold
                   text-[0.78rem] tracking-[0.15em] uppercase rounded-xl
                   transition-all duration-200 hover:-translate-y-px
                   hover:shadow-[0_0_30px_rgba(0,255,135,0.25),0_4px_20px_rgba(0,255,135,0.2)]
                   active:translate-y-0 active:shadow-none overflow-hidden">
            Sign In
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </button>
        </div>
      </form>

      <p class="up-in d-5 mt-6 text-center text-[0.78rem] text-[#4a5060]">
        No account?
        <a href="{{ route('register') }}" class="ml-1 text-[#00ff87] font-medium hover:text-white transition-colors">
          Create one
        </a>
      </p>

    </div>
  </div>
</div>
</x-guest-layout>