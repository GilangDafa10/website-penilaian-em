<template>
    <DashboardLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">
                    Daftar Pengguna
                </h1>
                <Link
                    href="usercontrol/create"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Tambah User
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4">
                <div
                    class="bg-green-50 border-l-4 border-green-400 p-4 rounded"
                >
                    <p class="text-green-700 text-sm">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    No
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    NIM
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Divisi
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Bidang
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="(user, index) in users.data"
                                :key="user.id_user"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{
                                        (users.current_page - 1) *
                                            users.per_page +
                                        index +
                                        1
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ user.nama }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{ user.nim }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{
                                        user.jabatan?.divisi ??
                                        `No Divisi (jabatan: ${JSON.stringify(
                                            user.jabatan
                                        )})`
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{
                                        user.jabatan?.bidang ??
                                        `No Bidang (jabatan: ${JSON.stringify(
                                            user.jabatan
                                        )})`
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{
                                        user.role?.nama_role ??
                                        `No Role (role: ${JSON.stringify(
                                            user.role
                                        )})`
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex items-center justify-end gap-3"
                                >
                                    <Link
                                        :href="`/usercontrol/${user.id_user}/edit`"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="openDeleteModal(user.id_user)"
                                        class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md shadow"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Modal Konfirmasi Hapus -->
                    <DeleteModal
                        :show="showDeleteModal"
                        title="Hapus Pengguna"
                        message="Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan."
                        confirm-text="Hapus"
                        @close="showDeleteModal = false"
                        @confirm="confirmDelete"
                    />
                </div>

                <!-- Pagination Controls -->
                <div
                    class="px-6 py-4 bg-gray-50 flex items-center justify-between"
                >
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ users.from }} sampai {{ users.to }} dari
                        {{ users.total }} pengguna
                    </div>
                    <div class="flex space-x-2">
                        <button
                            :disabled="!users.prev_page_url"
                            @click="visitPage(users.prev_page_url)"
                            class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Sebelumnya
                        </button>
                        <button
                            :disabled="!users.next_page_url"
                            @click="visitPage(users.next_page_url)"
                            class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref } from "vue";
import DeleteModal from "@/Components/DeleteModal.vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    users: Object,
});

// Debug: log data yang diterima
console.log("Users data:", props.users);
console.log("First user jabatan:", props.users?.data?.[0]?.jabatan);
console.log("First user role:", props.users?.data?.[0]?.role);

function visitPage(url) {
    if (url) {
        router.visit(url);
    }
}

// State untuk modal
const showDeleteModal = ref(false);
const userIdToDelete = ref(null);

// Buka modal & simpan ID
const openDeleteModal = (id) => {
    userIdToDelete.value = id;
    showDeleteModal.value = true;
};
const confirmDelete = () => {
    if (userIdToDelete.value) {
        router.delete(`/usercontrol/${userIdToDelete.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                userIdToDelete.value = null;
            },
        });
    }
};
</script>
