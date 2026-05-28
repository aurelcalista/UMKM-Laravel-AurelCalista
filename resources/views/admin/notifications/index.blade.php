@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1>Notifikasi</h1>
            <p>Semua notifikasi dan pengumuman sistem</p>
        </div>
        <div>
            <button onclick="markAllRead()" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-check"></i> Tandai semua dibaca
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="h6 mb-0">📢 Daftar Notifikasi</h3>
            <span class="badge bg-primary">{{ $notifications->total() }} total</span>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notif)
                <div class="list-group-item list-group-item-action {{ !$notif->is_read ? 'bg-light' : '' }}" 
                     data-id="{{ $notif->id }}" 
                     onclick="markAsRead({{ $notif->id }}, '{{ $notif->link }}')"
                     style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-2" style="background: 
                                    {{ $notif->type == 'success' ? '#d4edda' : ($notif->type == 'warning' ? '#fff3cd' : '#e2e3e5') }}">
                                    <i class="ti ti-bell" style="color: 
                                        {{ $notif->type == 'success' ? '#155724' : ($notif->type == 'warning' ? '#856404' : '#383d41') }}"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold {{ !$notif->is_read ? 'text-primary' : '' }}">
                                    {{ $notif->title }}
                                </div>
                                <div class="text-muted small">{{ $notif->message }}</div>
                                <div class="text-muted mt-1">
                                    <small>
                                        <i class="ti ti-clock"></i> 
                                        {{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @if(!$notif->is_read)
                        <span class="badge bg-primary rounded-pill">Baru</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-bell-off" style="font-size: 48px;"></i>
                    <p class="mt-3">Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<script>
function markAsRead(id, link) {
    fetch('/admin/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              if (link && link !== '#') {
                  window.location.href = link;
              } else {
                  location.reload();
              }
          }
      });
}

function markAllRead() {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(() => {
        location.reload();
    });
}

// Auto reload setiap 30 detik
setInterval(() => {
    location.reload();
}, 30000);
</script>
@endsection