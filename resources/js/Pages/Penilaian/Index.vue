<template>
    <DashboardLayout>
        <div class="min-h-screen bg-gray-100 p-6">
            <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
                <!-- Header -->
                <div
                    class="flex justify-between items-center mb-6 border-b pb-3"
                >
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">
                            Daftar Penilaian
                        </h1>
                        <p class="text-gray-500 text-sm">
                            <span class="font-medium">{{
                                auth.user.nama
                            }}</span>
                            ({{ auth.user.role?.nama_role ?? "Tanpa Role" }})
                        </p>
                    </div>
                    <div>
                        <button
                            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition"
                            @click="refreshPage"
                        >
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Daftar Target -->
                <div
                    v-if="daftarTarget.length > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"
                >
                    <div
                        v-for="target in daftarTarget"
                        :key="target.id_user"
                        class="p-5 bg-gray-50 border rounded-lg shadow-sm hover:shadow-md transition"
                    >
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start gap-2">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    {{ target.nama }}
                                </h2>
                                <span
                                    v-if="target.sudah_dinilai"
                                    class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium"
                                    title="Sudah dinilai"
                                >
                                    ✓
                                </span>
                            </div>
                            <span
                                class="text-xs px-2 py-1 rounded-full"
                                :class="{
                                    'bg-green-100 text-green-700':
                                        target.role?.nama_role === 'Staff',
                                    'bg-blue-100 text-blue-700':
                                        target.role?.nama_role === 'Kadiv',
                                    'bg-purple-100 text-purple-700':
                                        target.role?.nama_role === 'Kabid',
                                    'bg-gray-100 text-gray-600': !target.role,
                                }"
                            >
                                {{ target.role?.nama_role ?? "Tanpa Role" }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-medium">NIM:</span>
                            {{ target.nim ?? "-" }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-medium">Bidang:</span>
                            {{ target.jabatan?.bidang ?? "-" }}
                        </p>
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="font-medium">Divisi:</span>
                            {{ target.jabatan?.divisi ?? "-" }}
                        </p>

                        <div class="flex justify-end">
                            <Link
                                v-if="!target.sudah_dinilai"
                                :href="`/penilaian/create/${target.nim}`"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm"
                            >
                                Nilai Sekarang
                            </Link>
                            <button
                                v-else
                                class="bg-gray-400 text-white px-3 py-2 rounded-md text-sm cursor-not-allowed"
                                disabled
                                title="Anda sudah memberikan penilaian"
                            >
                                Sudah Dinilai
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Jika tidak ada target -->
                <div v-else class="text-center py-12 text-gray-500">
                    <p>Tidak ada anggota yang dapat Anda nilai saat ini.</p>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

defineProps({
    daftarTarget: Array,
    auth: Object,
});

const refreshPage = () => {
    router.reload({ only: ["daftarTarget"] });
};
</script>
