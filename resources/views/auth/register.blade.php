<x-guest-layout>
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap');

* { box-sizing: border-box; }

.auth-wrap {
    min-height: 100vh;
    background: #080b0f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', sans-serif;
    position: relative;
    overflow: hidden;
    padding: 2rem 1rem;
}

.auth-wrap::before {
    content: '';
    position: absolute;
    top: -20%;
    right: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(16,185,129,0.07) 0%, transparent 70%);
    pointer-events: none;
}
.auth-wrap::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(16,185,129,0.04) 0%, transparent 70%);
    pointer-events: none;
}

.grain {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-size: 200px 200px;
}

.auth-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    animation: cardIn 0.7s cubic-bezier(0.16,1,0.3,1) both;
}

@keyframes cardIn {
    from { opacity: 0; transform: translateY(32px); }
    to   { opacity: 1; transform: translateY(0); }
}

.auth-header {
    margin-bottom: 2.5rem;
    animation: cardIn 0.7s 0.05s cubic-bezier(0.16,1,0.3,1) both;
}

.auth-eyebrow {
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #10b981;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.auth-eyebrow::before {
    content: '';
    display: block;
    width: 20px;
    height: 1px;
    background: #10b981;
}

.auth-title {
    font-family: 'Syne', sans-serif;
    font-size: 2.6rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.05;
    letter-spacing: -0.02em;
}
.auth-title span { color: #10b981; }

.auth-body {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(12px);
    animation: cardIn 0.7s 0.1s cubic-bezier(0.16,1,0.3,1) both;
}

.field { margin-bottom: 1.25rem; }

.field-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.field-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(0,0,0,0.35);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    -webkit-appearance: none;
}

.field-input::placeholder { color: #334155; }

.field-input:focus {
    border-color: rgba(16,185,129,0.5);
    box-shadow: 0 0 0 3px rgba(16,185,129,0.1), 0 0 20px rgba(16,185,129,0.05);
    background: rgba(0,0,0,0.5);
}

.field-input.is-error {
    border-color: rgba(239,68,68,0.5);
    box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
}

.field-error {
    margin-top: 0.4rem;
    font-size: 0.75rem;
    color: #f87171;
}

.btn-primary {
    width: 100%;
    padding: 0.85rem 1.5rem;
    background: #10b981;
    color: #fff;
    font-family: 'Syne', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
    margin-top: 0.25rem;
}

.btn-primary::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
    pointer-events: none;
}

.btn-primary:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(16,185,129,0.3);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: none;
}

.auth-footer {
    margin-top: 1.5rem;
    text-align: center;
    font-size: 0.8rem;
    color: #475569;
    animation: cardIn 0.7s 0.15s cubic-bezier(0.16,1,0.3,1) both;
}

.auth-footer a {
    color: #10b981;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
.auth-footer a:hover { color: #34d399; }
</style>

<div class="auth-wrap">
    <div class="grain"></div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-eyebrow">Join us</div>
            <h1 class="auth-title">Create<br><span>account.</span></h1>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label class="field-label" for="name">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="field-input {{ $errors->has('name') ? 'is-error' : '' }}"
                        placeholder="Your name"
                    />
                    @if ($errors->has('name'))
                        <div class="field-error">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label class="field-label" for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                        placeholder="you@example.com"
                    />
                    @if ($errors->has('email'))
                        <div class="field-error">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                        placeholder="••••••••"
                    />
                    @if ($errors->has('password'))
                        <div class="field-error">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label class="field-label" for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="field-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                        placeholder="••••••••"
                    />
                    @if ($errors->has('password_confirmation'))
                        <div class="field-error">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn-primary">Create Account →</button>
            </form>
        </div>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>
</div>
</x-guest-layout>