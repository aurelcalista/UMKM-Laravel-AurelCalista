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
  }
  .sl-brand-badge {
    position: absolute;
    top: 2.5rem; left: 3rem;
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #fff;
  }
  .sl-brand-badge span { color: red; }

  .sl-form-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem 4rem;
    background: #F7F4F1;
  }
  .sl-form-inner { max-width: 450px; width: 100%; margin: 0 auto; }

  .sl-form-heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    font-weight: 600;
    color: #1A1412;
    margin-bottom: 0.35rem;
  }
  .sl-form-sub {
    font-size: 0.88rem;
    color: #8A7A74;
    margin-bottom: 2rem;
  }

  .temp-password-box {
    background: #FEF3C7;
    padding: 24px;
    border-radius: 16px;
    text-align: center;
    margin-bottom: 24px;
  }
  .temp-password-label {
    font-size: 0.85rem;
    color: #92400E;
    margin-bottom: 12px;
  }
  .temp-password-value {
    font-family: 'Courier New', monospace;
    font-size: 28px;
    font-weight: bold;
    letter-spacing: 2px;
    background: white;
    display: inline-block;
    padding: 12px 24px;
    border-radius: 12px;
    color: #8B1A1A;
    margin-bottom: 12px;
  }
  .temp-password-expiry {
    font-size: 0.75rem;
    color: #92400E;
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
    margin-bottom: 1rem;
  }
  .sl-submit:hover { background: #A84F2C; }

  .sl-back-link {
    text-align: center;
    margin-top: 1rem;
  }
  .sl-back-link a { color: #C0603A; text-decoration: none; }

  @media (max-width: 900px) {
    .sl-visual { display: none; }
    .sl-form-panel { padding: 2rem; }
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
      <div class="tagline">Password Sementara<br>Telah Dikirim</div>
    </div>
  </div>

  <div class="sl-form-panel">
    <div class="sl-form-inner">
      <h1 class="sl-form-heading">🔐 Password Sementara</h1>
      <p class="sl-form-sub">Gunakan password di bawah untuk login, lalu segera ganti password Anda.</p>

      <div class="temp-password-box">
        <div class="temp-password-label">Email:</div>
        <p style="font-weight: 500; margin-bottom: 16px;">{{ $email }}</p>
        
        <div class="temp-password-label">Password Sementara:</div>
        <div class="temp-password-value">{{ $temp_password }}</div>
        
        <div class="temp-password-expiry">
          ⏰ Berlaku hingga: {{ $expires_at->format('d M Y H:i') }}
        </div>
      </div>

      <form method="POST" action="{{ route('password.temp-login') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="password" value="{{ $temp_password }}">
        <button type="submit" class="sl-submit">Login dengan Password Sementara</button>
      </form>

      <div class="sl-back-link">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
      </div>
    </div>
  </div>
</div>
@endsection