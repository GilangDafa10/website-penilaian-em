<template>
    <DashboardLayout title="Hasil Penilaian">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                Hasil Penilaian
            </h2>

            <table class="min-w-full border rounded-lg overflow-hidden">
                <thead class="bg-blue-400">
                    <tr>
                        <th class="border p-2 text-center">Nama</th>
                        <th class="border p-2 text-center">
                            Tanggal Penilaian
                        </th>
                        <th
                            v-for="kriteria in kriterias"
                            :key="kriteria.id_kriteria"
                            class="border p-2 text-center"
                        >
                            {{ kriteria.nama_kriteria }}
                        </th>
                        <th class="border p-2 text-center">Rata-rata Semua</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in data"
                        :key="index"
                        class="hover:bg-gray-50"
                    >
                        <td class="border p-2">{{ row.nama }}</td>
                        <td class="border p-2 text-center">
                            {{ row.tanggal }}
                        </td>

                        <td
                            v-for="kriteria in kriterias"
                            :key="kriteria.id_kriteria"
                            class="border p-2 text-center"
                        >
                            {{ row[kriteria.nama_kriteria] }}
                        </td>

                        <td class="border p-2 font-semibold text-center">
                            {{ row.rata_rata_semua_kriteria }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="data.length === 0"
                class="text-center text-gray-500 mt-4"
            >
                Tidak ada data penilaian.
            </div>

            <div class="mt-6">
                <Link
                    href="/dashboard"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors"
                >
                    Kembali ke Dashboard
                </Link>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    data: Array,
    kriterias: Array,
});
</script>
