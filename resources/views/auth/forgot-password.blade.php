@extends('layouts.auth')

@section('content')

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .sl-page {
    display: flex;
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    background: #F7F4F1;
  }
  .sl-input-icon { position: relative; }
  .sl-input-icon i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #B7A9A2;
    font-size: 1rem;
    pointer-events: none;
  }
  .sl-input-icon input { padding-left: 46px !important; }

  .sl-visual {
    flex: 0 0 52%;
    position: relative;
    overflow: hidden;
    background: #1A1412;
  }
  .sl-visual img {
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0.72;
    display: block;
  }
  .sl-visual-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(160deg, rgba(90,30,20,0.45) 0%, rgba(10,10,10,0.6) 100%);
  }
  .sl-visual-copy {
    position: absolute;
    bottom: 3rem; left: 3rem; right: 3rem;
    color: #fff;
  }
  .sl-visual-copy .tagline {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 600;
    line-height: 1.25;
    margin-bottom: 0.75rem;
    letter-spacing: -0.01em;
  }
  .sl-visual-copy .sub {
    font-size: 0.9rem;
    font-weight: 300;
    opacity: 0.75;
    letter-spacing: 0.04em;
  }
  .sl-brand-badge {
    position: absolute;
    top: 2.5rem; left: 3rem;
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: -0.01em;
  }
  .sl-brand-badge span { color: red; }

  .sl-form-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem 4rem;
    background: #F7F4F1;
    overflow-y: auto;
  }
  .sl-form-inner { max-width: 400px; width: 100%; }

  .sl-top-brand {
    display: none;
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: #1A1412;
    margin-bottom: 2rem;
  }
  .sl-top-brand span { color: #C0603A; }

  .sl-form-heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    font-weight: 600;
    color: #1A1412;
    margin-bottom: 0.35rem;
    letter-spacing: -0.02em;
  }
  .sl-form-sub {
    font-size: 0.88rem;
    color: #8A7A74;
    font-weight: 300;
    margin-bottom: 2.2rem;
  }

  .sl-field { margin-bottom: 1.25rem; }
  .sl-field label {
    display: block;
    font-size: 0.78rem;
    font-weight: 500;
    color: #5A4A44;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 0.45rem;
  }
  .sl-field input {
    width: 100%;
    height: 46px;
    background: #fff;
    border: 1.5px solid #DDD5CF;
    border-radius: 8px;
    padding: 0 1rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.92rem;
    color: #1A1412;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .sl-field input:focus {
    border-color: #C0603A;
    box-shadow: 0 0 0 3px rgba(192,96,58,0.1);
  }

  .sl-submit {
    width: 100%;
    height: 48px;
    background: #C0603A;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 0.02em;
    transition: background 0.2s, transform 0.15s;
    margin-bottom: 1.5rem;
  }
  .sl-submit:hover { background: #A84F2C; }

  .sl-errors {
    background: #FEF0EB;
    border: 1px solid #F5C0A8;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.2rem;
  }
  .sl-error-item { font-size: 0.83rem; color: #A84F2C; padding: 2px 0; }

  .sl-success {
    background: #EAF5EE;
    border: 1px solid #A8D9B5;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.2rem;
    font-size: 0.83rem;
    color: #2E6B42;
  }

  .sl-back-link {
    text-align: center;
    margin-top: 1rem;
  }
  .sl-back-link a {
    color: #C0603A;
    text-decoration: none;
    font-size: 0.85rem;
  }
  .sl-back-link a:hover { text-decoration: underline; }

  @media (max-width: 900px) {
    .sl-visual { display: none; }
    .sl-form-panel { padding: 2.5rem 2rem; align-items: center; }
    .sl-top-brand { display: block; }
    .sl-form-inner { max-width: 440px; }
  }
  @media (max-width: 480px) {
    .sl-form-panel { padding: 2rem 1.25rem; }
  }
</style>

<div class="sl-page">

  <div class="sl-visual">
    <img src="https://images.unsplash.com/photo-1498654896293-37aacf113fd9?w=900&auto=format&fit=crop" alt="Korean food">
    <div class="sl-visual-overlay"></div>
    <div class="sl-brand-badge">
      Seoul<span>licious</span>
    </div>
    <div class="sl-visual-copy">
      <div class="tagline">
        Lupa Password?<br>
        Tenang, kami bantu.
      </div>
      <div class="sub">
        Admin akan memproses permintaan Anda.
      </div>
    </div>
  </div>

  <div class="sl-form-panel">
    <div class="sl-form-inner">
      <div class="sl-top-brand">
        Seoul<span>licious</span>
      </div>

      <h1 class="sl-form-heading">
        Lupa Password
      </h1>
      <p class="sl-form-sub">
        Masukkan email Anda, admin akan memproses reset password.
      </p>

      @if ($errors->any())
        <div class="sl-errors">
          @foreach ($errors->all() as $error)
            <div class="sl-error-item">⚠ {{ $error }}</div>
          @endforeach
        </div>
      @endif

      @if (session('success'))
        <div class="sl-success">
          ✓ {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="sl-errors">
          <div class="sl-error-item">⚠ {{ session('error') }}</div>
        </div>
      @endif

      {{-- FORM KIRIM REQUEST --}}
      <form method="POST" action="{{ route('password.send-request') }}">
        @csrf
        <div class="sl-field">
          <label for="email">Email</label>
          <div class="sl-input-icon">
            <i class="ti ti-mail"></i>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@kamu.com" required>
          </div>
        </div>
        <button type="submit" class="sl-submit">Kirim Permintaan</button>
      </form>

      <hr style="margin: 20px 0; border: none; border-top: 1px solid #E0D8D4;">

      {{-- FORM CEK STATUS --}}
      <p style="font-size: 0.85rem; color: #8A7A74; margin-bottom: 16px;">Sudah disetujui admin? Masukkan email untuk melihat password sementara:</p>
      <form method="POST" action="{{ route('password.check-status') }}">
        @csrf
        <div class="sl-field">
          <div class="sl-input-icon">
            <i class="ti ti-mail"></i>
            <input type="email" name="email" placeholder="Email Anda" required>
          </div>
        </div>
        <button type="submit" class="sl-submit" style="background: #D4A853;">Lihat Password Sementara</button>
      </form>

      <div class="sl-back-link">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
      </div>
    </div>
  </div>
</div>
@endsection