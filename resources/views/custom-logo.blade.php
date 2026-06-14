<div x-data :style="$store.sidebar.isOpen ? 'display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; justify-content: space-between; width: 100%; padding: 0.5rem 0.25rem 0.5rem 1.5rem; border-bottom: 1px solid rgba(156, 163, 175, 0.2);' : 'display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; padding: 0.5rem 0 0.5rem 0; gap: 1rem; border-bottom: 1px solid rgba(156, 163, 175, 0.2);'">
    <div :style="$store.sidebar.isOpen ? 'display: flex; flex-direction: row; align-items: center; gap: 0.75rem; overflow: hidden;' : 'display: flex; align-items: center; justify-content: center;'">
        <svg style="width: 2rem; height: 2rem; color: #c1121f; flex-shrink: 0;" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4 11h11a3 3 0 0 0 3-3V4H7a3 3 0 0 0-3 3v4zm16 2H9a3 3 0 0 0-3 3v4h11a3 3 0 0 0 3-3v-4z" />
        </svg>
        <span x-show="$store.sidebar.isOpen" style="font-size: 1.25rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: inherit;">SILARIZ.OS</span>
    </div>
    
    <!-- AlpineJS button to toggle Filament Sidebar -->
    <button x-on:click.prevent="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()" style="padding: 0.375rem; border-radius: 0.375rem; flex-shrink: 0; background: transparent; border: none; cursor: pointer; color: #9ca3af; display: flex; align-items: center; justify-content: center;">
        <svg style="width: 1.5rem; height: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>
