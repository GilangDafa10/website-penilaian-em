<!-- resources/js/Pages/Admin/UserControl/Edit.vue -->
<template>
    <DashboardLayout title="Edit User">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6">
                Edit Pengguna: {{ user.nama }}
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 grid-rows-auto gap-5">
                    <div>
                        <label class="block text-gray-700 mb-2">ID User</label>
                        <input
                            v-model="form.id_user"
                            type="text"
                            disabled
                            class="w-full p-2 border rounded bg-gray-100"
                        />
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Nama</label>
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
                        <label class="block text-gray-700 mb-2">NIM</label>
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
                        <label class="block text-gray-700 mb-2"
                            >Password (Kosongkan jika tidak diubah)</label
                        >
                        <div class="relative">
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="w-full p-2 pr-10 border rounded"
                                :class="{ 'border-red-500': errors.password }"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-indigo-600"
                                tabindex="-1"
                            >
                                <Eye v-if="!showPassword" class="h-5 w-5" />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <div
                            v-if="errors.password"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.password }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Role</label>
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
                        <label class="block text-gray-700 mb-2">Jabatan</label>
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
                        <label class="block text-gray-700 mb-2">Divisi</label>
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
                        <div
                            v-if="errors.id_jabatan"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.id_jabatan }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Bidang</label>
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
                        <div
                            v-if="errors.id_jabatan"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ errors.id_jabatan }}
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6">
                    <button
                        type="submit"
                        class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        :disabled="form.processing"
                    >
                        Perbarui
                    </button>
                    <Link
                        href="/usercontrol"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
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
import { Eye, EyeOff } from "lucide-vue-next";

const props = defineProps({
    user: Object,
    roles: Array,
    jabatans: Array,
});

// State untuk toggle show/hide password
const showPassword = ref(false);

const form = useForm({
    id_user: props.user.id_user,
    nim: props.user.nim,
    nama: props.user.nama,
    password: "",
    role_id: props.user.role_id,
    id_jabatan: props.user.id_jabatan,
});

const errors = usePage().props.errors;

const submit = () => {
    form.put(`/usercontrol/${props.user.id_user}`, {
        preserveScroll: true,
    });
};
</script>
