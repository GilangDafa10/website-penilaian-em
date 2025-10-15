<template>
    <DashboardLayout>
        <div class="min-h-screen bg-gray-100 p-6">
            <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
                <!-- Header -->
                <div
                    class="flex justify-between items-center border-b pb-4 mb-6"
                >
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">
                            Form Penilaian
                        </h1>
                        <p class="text-gray-500 text-sm">
                            Menilai:
                            <span class="font-medium text-gray-700">{{
                                target.nama
                            }}</span>
                            ({{ target.role?.nama_role ?? "Tanpa Role" }})
                        </p>
                    </div>
                </div>

                <!-- Data Target -->
                <div class="bg-gray-50 p-4 rounded-md mb-6 border">
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">NIM:</span> {{ target.nim }}
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Bidang:</span>
                        {{ target.jabatan?.bidang ?? "-" }}
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Divisi:</span>
                        {{ target.jabatan?.divisi ?? "-" }}
                    </p>
                </div>

                <!-- Form Penilaian -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div
                        v-for="krit in kriteria"
                        :key="krit.id_kriteria"
                        class="border-b pb-4"
                    >
                        <label class="block text-gray-800 font-medium mb-2">
                            {{ krit.nama_kriteria }}
                        </label>

                        <!-- Slider Penilaian -->
                        <div class="flex flex-col">
                            <input
                                type="range"
                                min="1"
                                max="5"
                                step="1"
                                v-model="form.nilai[krit.id_kriteria]"
                                class="w-full accent-indigo-600 cursor-pointer"
                            />

                            <!-- Nilai dan Keterangan -->
                            <div
                                class="flex justify-between text-sm mt-1 text-gray-600"
                            >
                                <span>Sangat Buruk</span>
                                <span>BuruK</span>
                                <span>Cukup</span>
                                <span>Baik</span>
                                <span>Sangat Baik</span>
                            </div>

                            <!-- Nilai saat ini -->
                            <div class="text-right mt-1">
                                <span
                                    class="text-indigo-700 font-semibold text-sm"
                                >
                                    Nilai:
                                    {{ form.nilai[krit.id_kriteria] ?? 3 }}
                                </span>
                            </div>
                        </div>

                        <!-- Error -->
                        <p
                            v-if="errors[`nilai.${krit.id_kriteria}`]"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ errors[`nilai.${krit.id_kriteria}`] }}
                        </p>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-between items-center pt-4">
                        <Link
                            :href="`/penilaian`"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-md transition text-sm flex items-center"
                        >
                            ← Kembali
                        </Link>
                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md transition"
                            :disabled="processing"
                        >
                            <span v-if="!processing">Simpan Penilaian</span>
                            <span v-else>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    target: Object,
    kriteria: Array,
});

const form = useForm({
    nim_dinilai: null,
    nilai: {},
});

const submitForm = () => {
    try {
        form.nim_dinilai = props.target?.nim;

        if (!form.nim_dinilai) {
            alert("Error: NIM target tidak ditemukan!");
            return;
        }

        if (Object.keys(form.nilai).length === 0) {
            alert("Error: Belum ada penilaian yang diberikan!");
            return;
        }

        form.post("/penilaian", {
            onSuccess: () => {
                alert("Penilaian berhasil disimpan!");
            },
            onError: (errors) => {
                alert("Error: " + JSON.stringify(errors));
            },
        });
    } catch (error) {
        alert("Terjadi error: " + error.message);
    }
};

const errors = form.errors;
const processing = form.processing;
</script>
