<template>
    <DashboardLayout title="Tambah User">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 grid-rows-auto gap-5">
                    <div>
                        <label class="block text-gray-700">Nama</label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.nama }"
                        />
                        <div
                            v-if="errors.nama"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.nama }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">NIM</label>
                        <input
                            v-model="form.nim"
                            type="text"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.nim }"
                        />
                        <div
                            v-if="errors.nim"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.nim }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.password }"
                        />
                        <div
                            v-if="errors.password"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.password }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">Role</label>
                        <select
                            v-model="form.role_id"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.role_id }"
                        >
                            <option value="">-- Pilih Role --</option>
                            <option
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.nama_role }}
                            </option>
                        </select>
                        <div
                            v-if="errors.role_id"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.role_id }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">Jabatan</label>
                        <select
                            v-model="form.id_jabatan"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.id_jabatan }"
                        >
                            <option value="">-- Pilih Jabatan --</option>
                            <option
                                v-for="jabatan in jabatans"
                                :key="jabatan.id_jabatan"
                                :value="jabatan.id_jabatan"
                            >
                                {{ jabatan.nama_jabatan }}
                            </option>
                        </select>
                        <div
                            v-if="errors.id_jabatan"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.id_jabatan }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">Divisi</label>
                        <select
                            v-model="form.id_jabatan"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.id_jabatan }"
                        >
                            <option value="">-- Pilih Divisi --</option>
                            <option
                                v-for="jabatan in jabatans"
                                :key="jabatan.id_jabatan"
                                :value="jabatan.id_jabatan"
                            >
                                {{ jabatan.divisi }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700">Bidang</label>
                        <select
                            v-model="form.id_jabatan"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': errors.id_jabatan }"
                        >
                            <option value="">-- Pilih Bidang --</option>
                            <option
                                v-for="jabatan in jabatans"
                                :key="jabatan.id_jabatan"
                                :value="jabatan.id_jabatan"
                            >
                                {{ jabatan.bidang }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button
                        type="submit"
                        class="cursor-pointer bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors"
                        :disabled="form.processing"
                    >
                        Simpan
                    </button>

                    <Link
                        href="/usercontrol"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors"
                    >
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    roles: Array,
    jabatans: Array,
});

const form = useForm({
    nama: "",
    nim: "",
    password: "",
    role_id: "",
    id_jabatan: "",
});

const errors = usePage().props.errors;

const submit = () => {
    form.post("/usercontrol", {
        preserveScroll: true,
    });
};
</script>
