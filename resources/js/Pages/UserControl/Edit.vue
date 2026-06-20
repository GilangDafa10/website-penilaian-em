<template>
    <DashboardLayout title="Edit User">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6">
                Edit Pengguna: {{ user.nama }}
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 gap-5">

                    <!-- NAMA -->
                    <div>
                        <label>Nama</label>
                        <input v-model="form.nama" type="text" class="w-full p-2 border rounded" />
                    </div>

                    <!-- NIM -->
                    <div>
                        <label>NIM</label>
                        <input v-model="form.nim" type="text" class="w-full p-2 border rounded" />
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label>Password (opsional)</label>
                        <input v-model="form.password" type="password" class="w-full p-2 border rounded" />
                    </div>

                    <!-- ROLE -->
                    <div>
                        <label>Role</label>
                        <select v-model="form.role_id" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Role --</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.nama_role }}
                            </option>
                        </select>
                    </div>

                    <!-- BIDANG -->
                    <div>
                        <label>Bidang</label>
                        <select v-model="form.bidang" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Bidang --</option>
                            <option v-for="b in bidangList" :key="b" :value="b">
                                {{ b }}
                            </option>
                        </select>
                    </div>

                    <!-- DIVISI -->
                    <div>
                        <label>Divisi</label>
                        <select v-model="form.divisi" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Divisi --</option>
                            <option v-for="d in divisiList" :key="d" :value="d">
                                {{ d }}
                            </option>
                        </select>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex space-x-3 mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Update
                    </button>

                    <Link href="/usercontrol" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { computed, watch } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Pages/Layouts/LayoutDashboard.vue";

const props = defineProps({
    user: Object,
    roles: Array,
    jabatans: Array,
});

// FORM
const form = useForm({
    nama: props.user.nama,
    nim: props.user.nim,
    password: "",
    role_id: props.user.role_id,
    bidang: "",
    divisi: "",
    id_jabatan: props.user.id_jabatan,
});

// BIDANG UNIQUE
const bidangList = computed(() => {
    return [...new Set(props.jabatans.map(j => j.bidang))];
});

// DIVISI FILTER
const divisiList = computed(() => {
    return [
        ...new Set(
            props.jabatans
                .filter(j => j.bidang === form.bidang)
                .map(j => j.divisi)
        )
    ];
});

// CARI JABATAN
const selectedJabatan = computed(() => {
    return props.jabatans.find(
        j => j.bidang === form.bidang && j.divisi === form.divisi
    );
});

// RESET DIVISI
watch(() => form.bidang, () => {
    form.divisi = "";
});

// SUBMIT FINAL
const submit = () => {

    if (!form.nama || !form.nim || !form.role_id) {
        alert("Data wajib diisi!");
        return;
    }

    if (form.bidang && form.divisi) {
        if (!selectedJabatan.value) {
            alert("Bidang & Divisi tidak valid!");
            return;
        }

        form.id_jabatan = selectedJabatan.value.id_jabatan;
    }

    form.put(`/usercontrol/${props.user.id_user}`, {
        onSuccess: () => alert("Berhasil update!"),
        onError: (err) => alert(JSON.stringify(err)),
    });
};
</script>