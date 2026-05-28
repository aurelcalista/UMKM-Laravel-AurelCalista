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
  .sl-form-inner { max-width: 400px; width: 100%; margin: 0 auto; }

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
    outline: none;
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
    margin-bottom: 1rem;
  }
  .sl-submit:hover { background: #A84F2C; }

  .sl-warning {
    background: #FEF3C7;
    border: 1px solid #FDE68A;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    color: #92400E;
    font-size: 0.85rem;
  }

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
      <div class="tagline">Ganti Password<br>Baru Anda</div>
    </div>
  </div>

  <div class="sl-form-panel">
    <div class="sl-form-inner">
      <h1 class="sl-form-heading">Ganti Password</h1>
      <p class="sl-form-sub">Buat password baru untuk akun Anda.</p>

      @if(session('warning'))
        <div class="sl-warning">
          ⚠ {{ session('warning') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.force-change.post') }}">
        @csrf
        <div class="sl-field">
          <label>Password Baru</label>
          <input type="password" name="new_password" required>
        </div>
        <div class="sl-field">
          <label>Konfirmasi Password Baru</label>
          <input type="password" name="new_password_confirmation" required>
        </div>
        <button type="submit" class="sl-submit">Ganti Password</button>
      </form>
    </div>
  </div>
</div>
@endsection