<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Eye, EyeOff } from "lucide-vue-next"; // ✅ Tambahkan baris ini
import logoEM from "../../images/logo-em.png";

// Form login dengan Inertia
const form = useForm({
    nim: "",
    password: "",
    remember: false,
});

// State untuk toggle show/hide password
const showPassword = ref(false);

// Kirim data login ke route Laravel (biasanya `login`)
const submit = () => {
    form.post("/login");
};
</script>

<template>
    <div class="bg-color min-h-screen flex items-center justify-center">
        <div
            class="flex flex-col items-center w-full max-w-md bg-gray-100 rounded-2xl shadow-lg p-8 space-y-6"
        >
            <img :src="logoEM" alt="Logo EM" class="h-25 w-auto" />

            <form @submit.prevent="submit" class="space-y-4 w-full">
                <!-- NIM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >NIM</label
                    >
                    <input
                        v-model="form.nim"
                        type="text"
                        class="p-1 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-1 focus:outline-indigo-800 focus:border-indigo-800"
                        required
                        autofocus
                    />
                    <div
                        v-if="form.errors.email"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.email }}
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Password</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="p-1 mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-1 focus:outline-indigo-800 focus:border-indigo-800 pr-10"
                            required
                        />
                        <!-- Tombol ikon mata -->
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
                        v-if="form.errors.password"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        v-model="form.remember"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition disabled:opacity-50 cursor-pointer"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing">Login</span>
                        <span v-else>Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
body {
    font-family: "Inter", sans-serif;
}
.bg-color {
    background: #ff6200;
    background: radial-gradient(
        circle,
        rgba(255, 98, 0, 1) 0%,
        rgba(1, 27, 84, 1) 100%
    );
}
</style>
