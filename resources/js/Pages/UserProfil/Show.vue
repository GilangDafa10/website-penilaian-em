<template>
    <DashboardLayout title="Profil Pengguna">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Profil Anda</h2>

            <div class="mb-6">
                <div class="flex flex-col gap-3">
                    <div>
                        <span class="block text-sm text-gray-500">Nama</span>
                        <p class="text-lg font-medium text-gray-800">
                            {{ user.nama }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">NIM</span>
                        <p class="text-lg font-medium text-gray-800">
                            {{ user.nim }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Divisi</span>
                        <p class="text-lg font-medium text-gray-800">
                            {{ user.jabatan?.divisi || "-" }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Bidang</span>
                        <p class="text-lg font-medium text-gray-800">
                            {{ user.jabatan?.bidang || "-" }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Role</span>
                        <p class="text-lg font-medium text-gray-800 capitalize">
                            {{ user.role?.nama_role || "-" }}
                        </p>
                    </div>
                </div>
            </div>

            <hr class="my-6" />

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Ubah Password
            </h3>

            <form @submit.prevent="submit" class="flex flex-col gap-4">
                <div>
                    <label class="block text-gray-700 mb-2"
                        >Password Saat Ini</label
                    >
                    <input
                        type="password"
                        v-model="form.current_password"
                        class="w-full p-2 border rounded"
                        :class="{
                            'border-red-500': form.errors.current_password,
                        }"
                    />
                    <p
                        v-if="form.errors.current_password"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.current_password }}
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2"
                        >Password Baru</label
                    >
                    <input
                        type="password"
                        v-model="form.new_password"
                        class="w-full p-2 border rounded"
                        :class="{ 'border-red-500': form.errors.new_password }"
                    />
                    <p
                        v-if="form.errors.new_password"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.new_password }}
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2"
                        >Konfirmasi Password Baru</label
                    >
                    <input
                        type="password"
                        v-model="form.new_password_confirmation"
                        class="w-full p-2 border rounded"
                    />
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors"
                    :disabled="form.processing"
                >
                    Simpan Perubahan
                </button>

                <p
                    v-if="success"
                    class="text-green-600 text-sm mt-3 font-medium"
                >
                    {{ success }}
                </p>
            </form>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    user: Object,
    success: String,
});

const form = useForm({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

const submit = () => {
    form.post("/profil", {
        preserveScroll: true,
    });
};
</script>
