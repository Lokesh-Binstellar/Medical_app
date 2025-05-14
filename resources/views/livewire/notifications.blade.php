<div>
    <!-- Bell Icon with Red Dot -->
   <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
   href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
    <i class="mdi mdi-bell-outline mdi-24px"></i>

    @if ($notificationCount > 0)
        <span class="position-absolute top-0 start-50 translate-middle-y badge bg-danger mt-2">
            {{ $notificationCount }}
        </span>
    @endif
</a>

<ul class="dropdown-menu dropdown-menu-end py-0">
    @forelse ($notifications as $notification)
        @php
    $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
@endphp

        <li class="dropdown-item">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <small class="d-block text-body">{{ $data['message'] ?? 'New Notification' }}</small>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </li>
    @empty
        <li class="dropdown-item text-muted">No notifications</li>
    @endforelse
</ul>

</div>
