@php
    $user = Auth::user();
    $unreadNotificationsCount = $user->unreadNotifications()->count();
    $notificationsCount = $user->notifications()->count();
@endphp

<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
            </div>


            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    

                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                            data-bs-toggle="dropdown" id="notificationDropdown">
                            <span class="alert-count" id="notification-count">{{ $unreadNotificationsCount }}</span>
                            <i class='bx bx-bell'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">Notifications</p>
                                    <p class="msg-header-badge" id="notification-badge">{{ $unreadNotificationsCount }}
                                        New</p>
                                </div>
                            </a>

                            <div class="header-notifications-list" id="notifications-container">
                                @forelse ($user->unreadNotifications->take(6) as $notification)
                                    <a class="dropdown-item notification-item" href="javascript:;"
                                        data-notification-id="{{ $notification->id }}">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-success text-danger">C</div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">New Orders
                                                    <span
                                                        class="msg-time float-end">{{ $notification->created_at->diffForHumans() }}</span>
                                                </h6>
                                                <p class="msg-info">{{ $notification->data['message'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <p class="dropdown-item text-center text-muted" id="no-notifications">No New
                                        Notifications.</p>
                                @endforelse
                            </div>

                            <a href="javascript:;">
                                <div class="text-center msg-footer">
                                    <button class="btn btn-primary w-100" id="mark-all-read"
                                        style="{{ $unreadNotificationsCount == 0 ? 'display: none;' : '' }}">
                                        Mark All as Read
                                    </button>
                                    <button class="btn btn-secondary w-100"
                                        style="{{ $notificationsCount == 0 ? 'display: none;' : '' }}"
                                        id="delete-all-notifications">
                                        Delete All Notifications
                                    </button>
                                </div>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large d-none">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span
                                class="alert-count">8</span>
                            <i class='bx bx-shopping-bag'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">My Cart</p>
                                    <p class="msg-header-badge">10 Items</p>
                                </div>
                            </a>
                            <div class="header-message-list">
                                
                             
                            </div>
                           
                        </div>
                    </li>
                </ul>
            </div>

            @php
                $id = Auth::id();
                $profileData = App\Models\User::find($id);
            @endphp

            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ !empty($profileData->photo) ? url('upload/instructor_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                        class="user-img" alt="user avatar">
                    <div class="user-info">
                        <p class="user-name mb-0">{{ $profileData->name }}</p>
                        <p class="designattion mb-0">{{ $profileData->email }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center"
                            href="{{ route('instructor.profile') }}"><i
                                class="bx bx-user fs-5"></i><span>Profile</span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center"
                            href="{{ route('instructor.change.password') }}"><i
                                class="bx bx-cog fs-5"></i><span>Change
                                Password</span></a>
                    </li>
        
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
                    </li>
                  
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('instructor.logout') }}"><i
                                class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

@push('scripts')
    <script>
        $(document).ready(function() {
            // CSRF Token untuk semua AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Function untuk update notification counter
            function updateNotificationCounter(count) {
                $('#notification-count').text(count);
                $('#notification-badge').text(count + ' New');

                if (count == 0) {
                    $('#notification-count').hide();
                    $('#mark-all-read').hide();
                } else {
                    $('#notification-count').show();
                    $('#mark-all-read').show();
                }
            }

            // Function untuk load notifications
            function loadNotifications() {
                $.ajax({
                    url: '{{ route('notifications.unread') }}',
                    type: 'GET',
                    success: function(response) {
                        updateNotificationCounter(response.count);

                        let notificationsHtml = '';
                        if (response.notifications.length > 0) {
                            response.notifications.forEach(function(notification) {
                                notificationsHtml += `
                            <a class="dropdown-item notification-item" href="javascript:;" data-notification-id="${notification.id}">
                                <div class="d-flex align-items-center">
                                    <div class="notify bg-light-success text-danger">C</div>
                                    <div class="flex-grow-1">
                                        <h6 class="msg-name">New Orders 
                                            <span class="msg-time float-end">${notification.created_at_human}</span>
                                        </h6>
                                        <p class="msg-info">${notification.message}</p>
                                    </div>
                                </div>
                            </a>
                        `;
                            });
                        } else {
                            notificationsHtml =
                                '<p class="dropdown-item text-center text-muted" id="no-notifications">No New Notifications.</p>';
                        }

                        $('#notifications-container').html(notificationsHtml);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error loading notifications');
                    }
                });
            }

            // Click pada notification item untuk mark as read
            $(document).on('click', '.notification-item', function(e) {
                e.preventDefault();

                let notificationId = $(this).data('notification-id');
                let $notificationItem = $(this);

                $.ajax({
                    url: '{{ url('/notifications') }}/' + notificationId + '/mark-as-read',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            // Remove notification dari list
                            $notificationItem.fadeOut(300, function() {
                                $(this).remove();

                                // Update counter
                                updateNotificationCounter(response.unread_count);

                                // Jika tidak ada notification, tampilkan pesan
                                if (response.unread_count == 0) {
                                    $('#notifications-container').html(
                                        '<p class="dropdown-item text-center text-muted" id="no-notifications">No New Notifications.</p>'
                                        );
                                }
                            });

                            toastr.success('Notification marked as read');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Failed to mark notification as read');
                    }
                });
            });

            // Mark All as Read button
            $('#mark-all-read').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('notifications.markAllAsRead') }}',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            // Clear semua notifications
                            $('#notifications-container').html(
                                '<p class="dropdown-item text-center text-muted" id="no-notifications">No New Notifications.</p>'
                                );

                            // Update counter
                            updateNotificationCounter(0);

                            toastr.success('All notifications marked as read');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Failed to mark all notifications as read');
                    }
                });
            });

            // Auto refresh notifications setiap 30 detik
            setInterval(function() {
                loadNotifications();
            }, 30000); // 30 seconds

            // Load notifications saat dropdown dibuka
            $('#notificationDropdown').on('click', function() {
                loadNotifications();
            });

            // View All Notifications button (optional - redirect ke halaman notifications)
            $('#delete-all-notifications').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('notifications.clearAll') }}',
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            // Clear semua notifications
                            $('#delete-all-notifications').hide();

                            // Update counter
                            updateNotificationCounter(0);

                            toastr.success('All notifications deleted');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Failed to delete all notifications');
                    }
                });
            });
        });
    </script>
@endpush
