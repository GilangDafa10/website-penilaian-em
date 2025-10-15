<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Mobile Overlay -->
        <div
            v-if="isMobileMenuOpen"
            @click="closeMobileMenu"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            class="bg-white text-gray-800 transition-all duration-300 flex flex-col fixed lg:sticky top-0 h-screen z-50"
            :class="[
                isCollapsed ? 'w-20' : 'w-64',
                isMobileMenuOpen
                    ? 'translate-x-0'
                    : '-translate-x-full lg:translate-x-0',
            ]"
        >
            <!-- Header -->
            <div
                class="p-3 border-b border-gray-700 flex items-center justify-start gap-3.5"
            >
                <img :src="logoEM" alt="Logo EM" class="h-9 w-auto" />
                <span
                    class="font-bold text-xl"
                    :class="{ hidden: isCollapsed }"
                >
                    E-Mailkomp
                </span>
                <button
                    @click="closeMobileMenu"
                    class="text-gray-400 lg:hidden hover:text-white"
                >
                    ✕
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <Link
                    v-for="item in menuItems"
                    :key="item.href"
                    :href="item.href"
                    @click="closeMobileMenu"
                    class="flex items-center p-3 rounded-lg hover:bg-gray-200 transition"
                    :class="{ 'bg-gray-300': isActive(item.href) }"
                >
                    <span v-if="!isCollapsed" class="text-gray-900">{{
                        item.name
                    }}</span>
                </Link>
            </nav>

            <!-- Collapse Button (Desktop only) -->
            <div class="p-4 border-t border-gray-700 hidden lg:block">
                <button
                    @click="toggleSidebar"
                    class="text-sm text-gray-300 hover:text-white w-full text-left"
                >
                    {{ isCollapsed ? "→" : "← Collapse" }}
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 w-full lg:w-auto">
            <!-- Top Navbar -->
            <header
                class="bg-white shadow-sm p-3 sm:p-4 flex justify-between items-center sticky top-0 z-30"
            >
                <!-- Mobile Menu Button -->
                <button
                    @click="toggleMobileMenu"
                    class="lg:hidden text-gray-600 hover:text-gray-900 p-2"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-2 sm:space-x-4 ml-auto">
                    <span
                        class="text-white bg-amber-600 px-2 sm:px-3 py-1 rounded-md font-medium text-xs sm:text-sm truncate max-w-[120px] sm:max-w-none"
                    >
                        Halo, {{ $page.props.auth.user?.nama }}
                    </span>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="cursor-pointer bg-red-600 text-white px-2 sm:px-3 py-1 rounded-lg font-medium text-xs sm:text-sm hover:bg-red-700 transition"
                        preserve-scroll
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main
                class="flex-1 p-3 sm:p-4 md:p-6 bg-gray-100 overflow-x-hidden"
            >
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import logoEM from "../../../images/logo-em.png";

const page = usePage();
const userRole = page.props.auth?.user?.role?.nama_role || null;

// --- Definisi menu untuk tiap grup ---
const adminMenu = [
    { name: "Dashboard", href: "/dashboard" },
    { name: "User Control", href: "/usercontrol" },
    { name: "Hasil Penilaian", href: "/hasilnilai" },
];

const userMenu = [
    { name: "Dashboard", href: "/dashboard" },
    { name: "Profil", href: "/profil" },
    { name: "Penilaian", href: "/penilaian" },
];

// --- Tentukan grup role ---
const isAdminGroup = computed(() => userRole === "Admin");
const isUserGroup = computed(() =>
    ["Kahim", "Wakahim", "Kabid", "Kadiv", "Staff"].includes(userRole)
);

// --- Filter menu sesuai grup ---
const menuItems = computed(() => {
    if (isAdminGroup.value) return adminMenu;
    if (isUserGroup.value) return userMenu;
    return [];
});

// --- Sidebar collapse state (desktop) ---
const isCollapsed = ref(false);
const toggleSidebar = () => (isCollapsed.value = !isCollapsed.value);

// --- Mobile menu state ---
const isMobileMenuOpen = ref(false);
const toggleMobileMenu = () =>
    (isMobileMenuOpen.value = !isMobileMenuOpen.value);
const closeMobileMenu = () => (isMobileMenuOpen.value = false);

// --- Active link detection ---
const isActive = (path) => page.url.startsWith(path);

// --- Simpan state collapse ke localStorage ---
onMounted(() => {
    const saved = localStorage.getItem("sidebarCollapsed");
    if (saved) isCollapsed.value = JSON.parse(saved);

    // Auto-close mobile menu on desktop
    const handleResize = () => {
        if (window.innerWidth >= 1024) {
            isMobileMenuOpen.value = false;
        }
    };

    window.addEventListener("resize", handleResize);

    // Cleanup
    return () => window.removeEventListener("resize", handleResize);
});

watch(isCollapsed, (val) => {
    localStorage.setItem("sidebarCollapsed", JSON.stringify(val));
});

// Close mobile menu when route changes
watch(
    () => page.url,
    () => {
        closeMobileMenu();
    }
);
</script>
