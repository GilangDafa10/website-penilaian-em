<template>
    <DashboardLayout title="Tambah User">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 gap-5">

                    <div>
                        <label>Nama</label>
                        <input v-model="form.nama" class="w-full p-2 border rounded" />
                        <div v-if="errors.nama" class="text-red-500 text-sm">{{ errors.nama }}</div>
                    </div>

                    <div>
                        <label>NIM</label>
                        <input v-model="form.nim" class="w-full p-2 border rounded" />
                        <div v-if="errors.nim" class="text-red-500 text-sm">{{ errors.nim }}</div>
                    </div>

                    <div>
                        <label>Password</label>
                        <input v-model="form.password" type="password" class="w-full p-2 border rounded" />
                    </div>

                    <div>
                        <label>Role</label>
                        <select v-model="form.role_id" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Role --</option>
                            <option v-for="r in roles" :key="r.id" :value="r.id">
                                {{ r.nama_role }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label>Bidang</label>
                        <select v-model="form.bidang" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Bidang --</option>
                            <option v-for="b in bidangList" :key="b" :value="b">{{ b }}</option>
                        </select>
                    </div>

                    <div>
                        <label>Divisi</label>
                        <select v-model="form.divisi" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Divisi --</option>
                            <option v-for="d in divisiList" :key="d" :value="d">{{ d }}</option>
                        </select>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <button class="bg-green-600 text-white px-4 py-2 rounded">
                        Simpan
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
    bidang: "",
    divisi: "",
    id_jabatan: "",
});

const errors = usePage().props.errors;

// bidang unik
const bidangList = computed(() => {
    return [...new Set(props.jabatans.map(j => j.bidang))];
});

// divisi sesuai bidang
const divisiList = computed(() => {
    return [
        ...new Set(
            props.jabatans
                .filter(j => j.bidang === form.bidang)
                .map(j => j.divisi)
        )
    ];
});

// cari id_jabatan
const selectedJabatan = computed(() => {
    return props.jabatans.find(
        j => j.bidang === form.bidang && j.divisi === form.divisi
    );
});

// reset divisi kalau bidang berubah
watch(() => form.bidang, () => {
    form.divisi = "";
});

const submit = () => {

    if (!form.nama || !form.nim || !form.password || !form.role_id) {
        alert("Semua data wajib diisi!");
        return;
    }

    if (!selectedJabatan.value) {
        alert("Pilih bidang & divisi dulu!");
        return;
    }

    form.id_jabatan = selectedJabatan.value.id_jabatan;

    form.post("/usercontrol");
};
</script>