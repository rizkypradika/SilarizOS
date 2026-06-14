<x-filament-panels::page>
<style>
.silariz-dashboard {
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, #c1121f 0%, #780000 100%);
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 5.5rem;
    position: relative;
    overflow: hidden;
}
.welcome-card::before {
    content: '';
    position: absolute;
    top: -1.5rem; right: -1.5rem;
    width: 6rem; height: 6rem;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
}
.welcome-card::after {
    content: '';
    position: absolute;
    bottom: -2rem; right: 2rem;
    width: 4rem; height: 4rem;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.welcome-card .greeting { font-size: 1rem; font-weight: 400; opacity: 0.85; }
.welcome-card .name     { font-size: 1.3rem; font-weight: 700; margin-top: 0.1rem; }

/* Stats inside left panel */
.stat-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.stat-row:last-child { border-bottom: none; }
.stat-icon {
    width: 2.5rem; height: 2.5rem;
    border-radius: 0.75rem;
    background: #fff5f5;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { color: #c1121f; width: 1.2rem; height: 1.2rem; }
.stat-label { font-size: 0.75rem; color: #6b7280; }
.stat-value { font-size: 1rem; font-weight: 700; color: #111827; margin-top: 0.1rem; }

.dark .stat-row { border-color: #1e293b; }
.dark .stat-icon { background: #1e293b; }
.dark .stat-label { color: #9ca3af; }
.dark .stat-value { color: #f1f5f9; }

/* Action buttons */
.action-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.6rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.875rem; font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
}
.btn-outline {
    background: #fff;
    border: 1.5px solid #c1121f;
    color: #c1121f;
}
.btn-outline:hover { background: #fff5f5; }
.btn-primary {
    background: linear-gradient(135deg, #c1121f, #780000);
    color: #fff;
    box-shadow: 0 4px 14px rgba(193,18,31,0.35);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(193,18,31,0.4); }

.dark .btn-outline { background: #1e293b; border-color: #c1121f; color: #c1121f; }
.dark .btn-outline:hover { background: #2d1a1a; }

/* Hero Banner */
.hero-banner {
    background: linear-gradient(135deg, #e63946 0%, #c1121f 60%, #780000 100%);
    border-radius: 1rem;
    padding: 1.5rem 2rem;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 11rem;
    position: relative;
    overflow: hidden;
}
.hero-banner::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.05) 0%, transparent 50%),
                      radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 0%, transparent 40%);
}
.hero-text h2 {
    font-size: 1.6rem; font-weight: 800; line-height: 1.2;
    text-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.hero-text p { font-size: 0.8rem; opacity: 0.85; margin-top: 0.5rem; max-width: 20rem; }
.hero-emoji {
    font-size: 4rem;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
    flex-shrink: 0;
}

/* Monthly stats cards */
.month-card {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.month-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.month-card-icon {
    width: 3rem; height: 3rem;
    border-radius: 0.875rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.month-card-label { font-size: 0.8rem; color: #6b7280; }
.month-card-value { font-size: 1.15rem; font-weight: 700; color: #111827; margin-top: 0.2rem; }

.dark .month-card { background: #0f172a; border-color: #1e293b; }
.dark .month-card-label { color: #9ca3af; }
.dark .month-card-value { color: #f1f5f9; }

/* News card */
.news-card {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.dark .news-card { background: #0f172a; border-color: #1e293b; }
.news-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.dark .news-header { border-color: #1e293b; color: #f1f5f9; }
.news-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f9fafb;
}
.dark .news-item { border-color: #1e293b; }
.news-item:last-child { border-bottom: none; }
.news-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.72rem; font-weight: 600;
    margin-right: 0.5rem;
}
.badge-date { background: #ede9fe; color: #7c3aed; }
.badge-info { background: #dbeafe; color: #1d4ed8; }
.badge-promo { background: #fef3c7; color: #b45309; }
.news-content { font-size: 0.85rem; color: #374151; margin-top: 0.5rem; line-height: 1.5; }
.dark .news-content { color: #cbd5e1; }

/* Left panel */
.left-panel {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.dark .left-panel { background: #0f172a; border-color: #1e293b; }
.panel-body { padding: 1.25rem 1.5rem; }

/* Grid layout */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.month-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 768px) {
    .dashboard-grid { grid-template-columns: 1fr; }
    .month-grid { grid-template-columns: 1fr; }
}
</style>

<div class="silariz-dashboard" style="padding: 0.5rem 0; max-width: 100%;">

    {{-- Top Grid: Left Panel + Hero Banner --}}
    <div class="dashboard-grid">

        {{-- Left: Welcome Card + Stats --}}
        <div class="left-panel">
            {{-- Welcome --}}
            <div class="welcome-card">
                <div>
                    <div class="greeting">Selamat Datang 👋</div>
                    <div class="name">{{ $user->name }}</div>
                </div>
                <div style="font-size:2.5rem; opacity:0.6;">🛍️</div>
            </div>

            {{-- Stats --}}
            <div class="panel-body">
                <div class="stat-row">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-label">Saldo</div>
                        <div class="stat-value">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-row">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-label">Total Pesanan</div>
                        <div class="stat-value">{{ $totalOrders }} Pesanan</div>
                    </div>
                </div>

                <div class="stat-row">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-label">Total Deposit</div>
                        <div class="stat-value">Rp {{ number_format($totalDepositsThisMonth, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display:flex; gap:0.75rem; margin-top:1rem; flex-wrap:wrap;">
                    <a href="{{ route('filament.customer.resources.deposits.create') }}" class="action-btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Deposit
                    </a>
                    <a href="{{ route('filament.customer.resources.orders.create') }}" class="action-btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Buat Pesanan
                    </a>
                </div>
            </div>
        </div>

        {{-- Right: Hero Banner --}}
        <div class="hero-banner">
            <div class="hero-text" style="position:relative;z-index:1;">
                <h2>Terjadi Kendala?<br>Tenang Aja! 😊</h2>
                <p>Tim support kami siap membantu 7×24 Jam!<br>Hubungi kami via bantuan tiket di bawah.</p>
                <a href="{{ route('filament.customer.pages.team-chat') }}"
                   style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:1rem;padding:0.6rem 1.25rem;background:rgba(255,255,255,0.2);backdrop-filter:blur(8px);border:1.5px solid rgba(255,255,255,0.4);border-radius:9999px;color:#fff;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    💬 Chat Bantuan
                </a>
            </div>
            <div class="hero-emoji" style="position:relative;z-index:1;">🎧</div>
        </div>
    </div>

    {{-- Monthly Stats --}}
    <div class="month-grid">
        <div class="month-card">
            <div class="month-card-icon" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#b45309;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
            <div>
                <div class="month-card-label">Total Pesanan Bulan Ini</div>
                <div class="month-card-value">Rp {{ number_format($totalOrdersThisMonth, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="month-card">
            <div class="month-card-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#15803d;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div>
                <div class="month-card-label">Total Deposit Bulan Ini</div>
                <div class="month-card-value">Rp {{ number_format($totalDepositsThisMonth, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- News Section --}}
    <div class="news-card">
        <div class="news-header">
            📰 &nbsp;Informasi & Pengumuman
        </div>

        <div class="news-item">
            <div>
                <span class="news-badge badge-date">📅 {{ now()->format('d M Y') }}</span>
                <span class="news-badge badge-info">ℹ️ INFORMASI</span>
            </div>
            <div class="news-content">
                Selamat datang di <strong>SILARIZ.OS</strong> — Platform berlangganan digital terpercaya. Nikmati berbagai layanan streaming premium dengan harga terjangkau. Hubungi support jika ada kendala!
            </div>
        </div>

        <div class="news-item">
            <div>
                <span class="news-badge badge-date">📅 {{ now()->subDays(3)->format('d M Y') }}</span>
                <span class="news-badge badge-promo">🎁 PROMO</span>
            </div>
            <div class="news-content">
                Deposit sekarang dan nikmati kemudahan berbelanja produk digital. Saldo deposit bisa digunakan untuk semua jenis pesanan tanpa batas minimum!
            </div>
        </div>
    </div>

</div>
</x-filament-panels::page>
