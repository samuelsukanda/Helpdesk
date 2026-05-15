<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'IT Helpdesk') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('images/help-desk.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="sidebar flex flex-col" id="sidebar">
            {{-- Logo --}}
            <div class="p-4 border-b border-gray-800/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i class="fas fa-headset text-white text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="font-bold text-lg text-white leading-tight truncate">IT Helpdesk</h1>
                        <p class="text-gray-500 text-xs mt-0.5">Ticketing System</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-3 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section">Tiket</div>

                @can('ticket.create')
                    <a href="{{ route('tickets.create') }}"
                        class="nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Buat Tiket</span>
                    </a>
                @endcan

                <a href="{{ route('tickets.index') }}"
                    class="nav-link {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>
                        @role('user')
                            Tiket Saya
                        @else
                            Semua Tiket
                        @endrole
                    </span>
                </a>

                <div class="nav-section">Knowledge</div>
                <a href="{{ route('knowledge.index') }}"
                    class="nav-link {{ request()->routeIs('knowledge.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>Knowledge Base</span>
                </a>

                @role('admin|agent')
                    <div class="nav-section">Laporan</div>
                    <a href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                @endrole

                @role('admin')
                    <div class="nav-section">Admin</div>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Pengguna</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}"
                        class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Departemen</span>
                    </a>
                    <a href="{{ route('admin.sla.index') }}"
                        class="nav-link {{ request()->routeIs('admin.sla.*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <span>SLA Policy</span>
                    </a>
                @endrole
            </nav>

            {{-- User Profile --}}
            <div class="p-3 border-t border-gray-800/50">
                <div class="user-card">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff' }}"
                            alt="avatar" class="user-avatar"
                            onerror="this.src='https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff'">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 capitalize">
                                {{ auth()->user()->getRoleNames()->first() ?? 'User' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit"
                                class="w-7 h-7 rounded-md bg-gray-800 hover:bg-red-500/20 hover:text-red-400 text-gray-400 flex items-center justify-center transition-all duration-200"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="main-content flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- TOPBAR --}}
            <header class="topbar px-4 lg:px-6 py-2.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button class="hamburger" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 truncate">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Notification Dropdown --}}
                    <div class="relative" id="notificationDropdown">
                        <button class="notification-btn" onclick="toggleNotifications(event)" aria-label="Notifications"
                            aria-expanded="false" id="notifBtn">
                            <i class="fas fa-bell text-lg"></i>
                            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                            @if ($unread > 0)
                                <span class="notification-badge"
                                    id="notifBadge">{{ $unread > 99 ? '99+' : $unread }}</span>
                            @endif
                        </button>

                        {{-- Dropdown Panel --}}
                        <div class="notification-panel hidden" id="notifPanel">
                            <div class="notif-header">
                                <h4 class="font-bold text-gray-800 text-sm">Notifikasi</h4>
                                @if ($unread > 0)
                                    <button onclick="markAllRead()"
                                        class="text-xs text-blue-600 hover:text-blue-700 font-semibold">
                                        Tandai dibaca
                                    </button>
                                @endif
                            </div>
                            <div class="notif-list" id="notifList">
                                @forelse(auth()->user()->notifications()->take(8)->get() as $notif)
                                    @php
                                        $data = $notif->data;
                                        $isUnread = is_null($notif->read_at);
                                        $icon = $data['icon'] ?? 'fa-bell';
                                        $color = $data['color'] ?? 'blue';
                                        $colors = [
                                            'blue' => 'bg-blue-50 text-blue-600',
                                            'green' => 'bg-emerald-50 text-emerald-600',
                                            'red' => 'bg-red-50 text-red-600',
                                            'amber' => 'bg-amber-50 text-amber-600',
                                            'purple' => 'bg-purple-50 text-purple-600',
                                        ];
                                        $iconClass = $colors[$color] ?? $colors['blue'];
                                    @endphp

                                    <div class="relative group" id="notif-{{ $notif->id }}">

                                        <a href="{{ $data['url'] ?? route('notifications.index') }}"
                                            class="notif-item {{ $isUnread ? 'unread' : '' }} pr-6"
                                            onclick="markRead('{{ $notif->id }}', event)">
                                            <div class="notif-icon {{ $iconClass }}">
                                                <i class="fas {{ $icon }} text-xs"></i>
                                            </div>
                                            <div class="notif-content">
                                                <p class="notif-title">{{ $data['title'] ?? 'Notifikasi' }}</p>
                                                <p class="notif-desc">{{ $data['message'] ?? '' }}</p>
                                                <span
                                                    class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>

                                        <div class="absolute top-2 right-2 flex items-center gap-1">
                                            @if ($isUnread)
                                                <div class="notif-dot group-hover:hidden"
                                                    id="dot-{{ $notif->id }}"></div>
                                            @endif

                                            <button onclick="deleteNotif('{{ $notif->id }}', event)"
                                                title="Hapus notifikasi"
                                                class="w-5 h-5 rounded-full
                                                        flex items-center justify-center
                                                        text-gray-300 hover:text-red-500 hover:bg-red-50
                                                        opacity-0 group-hover:opacity-100
                                                        transition-all duration-150
                                                        focus:opacity-100 focus:outline-none">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>

                                    </div>

                                @empty
                                    <div class="notif-empty" id="notifEmpty">
                                        <i class="fas fa-bell-slash text-2xl text-gray-300 mb-2"></i>
                                        <p class="text-sm text-gray-400">Tidak ada notifikasi</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="notif-footer">
                                <a href="{{ route('notifications.index') }}" class="notif-view-all">
                                    Lihat semua notifikasi <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <span
                        class="hidden sm:inline text-sm text-gray-500 font-medium">{{ now()->format('d M Y') }}</span>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle text-lg flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle text-lg flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // SIDEBAR TOGGLE
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("sidebarOverlay");
        const hamburger = document.getElementById("hamburgerBtn");

        function toggleSidebar() {
            sidebar.classList.toggle("open");
            overlay.classList.toggle("active");
            hamburger.classList.toggle("active");
            document.body.style.overflow = sidebar.classList.contains("open") ? "hidden" : "";
        }

        document.querySelectorAll(".nav-link").forEach((link) => {
            link.addEventListener("click", () => {
                if (window.innerWidth <= 1024) toggleSidebar();
            });
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && sidebar.classList.contains("open")) toggleSidebar();
        });

        let resizeTimer;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove("open");
                    overlay.classList.remove("active");
                    hamburger.classList.remove("active");
                    document.body.style.overflow = "";
                }
            }, 100);
        });

        // NOTIFICATION DROPDOWN
        const notifDropdown = document.getElementById("notificationDropdown");
        const notifPanel = document.getElementById("notifPanel");
        const notifBtn = document.getElementById("notifBtn");

        function toggleNotifications(e) {
            e.stopPropagation();
            const isHidden = notifPanel.classList.contains("hidden");

            document.querySelectorAll(".notification-panel").forEach((p) => {
                if (p !== notifPanel) p.classList.add("hidden");
            });

            if (isHidden) {
                notifPanel.classList.remove("hidden");
                notifBtn.setAttribute("aria-expanded", "true");
            } else {
                notifPanel.classList.add("hidden");
                notifBtn.setAttribute("aria-expanded", "false");
            }
        }

        document.addEventListener("click", (e) => {
            if (!notifDropdown.contains(e.target)) {
                notifPanel.classList.add("hidden");
                notifBtn.setAttribute("aria-expanded", "false");
            }
        });

        // Mark single notification as read
        function markRead(id, e) {
            fetch(`/notifications/${id}/mark-as-read`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                    Accept: "application/json",
                },
            }).catch(() => {});
        }

        // Mark all as read
        function markAllRead() {
            fetch("/notifications/mark-all-as-read", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                        Accept: "application/json",
                    },
                })
                .then(() => {
                    document.querySelectorAll(".notif-item.unread").forEach((item) => {
                        item.classList.remove("unread");
                    });
                    document.querySelectorAll("[id^='dot-']").forEach((dot) => dot.remove());
                    const badge = document.getElementById("notifBadge");
                    if (badge) badge.remove();
                })
                .catch(() => {});
        }

        function deleteNotif(id, e) {
            e.preventDefault();
            e.stopPropagation();

            const el = document.getElementById("notif-" + id);
            if (el) {
                el.style.transition = "opacity 0.2s, transform 0.2s";
                el.style.opacity = "0";
                el.style.transform = "translateX(8px)";
                setTimeout(() => {
                    el.remove();
                    const list = document.getElementById("notifList");
                    const items = list.querySelectorAll("[id^='notif-']");
                    if (items.length === 0) {
                        list.innerHTML = `
                            <div class="notif-empty" id="notifEmpty">
                                <i class="fas fa-bell-slash text-2xl text-gray-300 mb-2"></i>
                                <p class="text-sm text-gray-400">Tidak ada notifikasi</p>
                            </div>`;
                    }
                }, 200);
            }

            const badge = document.getElementById("notifBadge");
            if (badge) {
                const current = parseInt(badge.textContent) || 0;
                if (current <= 1) {
                    badge.remove();
                } else {
                    badge.textContent = current - 1;
                }
            }

            fetch(`/notifications/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                    "Accept": "application/json",
                },
            }).catch(() => {});
        }

        document.querySelectorAll(".alert").forEach((alert) => {
            setTimeout(() => {
                alert.style.opacity = "0";
                alert.style.transform = "translateY(-10px)";
                alert.style.transition = "all 0.3s ease";
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>

    {{-- DataTables Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    {{-- SweetAlert2 CDN v11.26.24 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.24/dist/sweetalert2.all.min.js"></script>

    @stack('scripts')
</body>

</html>
