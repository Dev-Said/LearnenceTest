<template>
    <Head title="Create a poll" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create a poll
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="space-y-6 bg-white py-6 px-4 sm:p-6">
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3">
                                        <InputLabel
                                            for="title"
                                            value="Your question"
                                        />
                                        <TextInput
                                            id="title"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.title"
                                            autofocus
                                            autocomplete="off"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.title"
                                        />
                                    </div>
                                    <div class="col-span-1 block">
                                        <label class="flex items-center">
                                            <Checkbox
                                                name="publish"
                                                v-model:checked="form.publish"
                                            />
                                            <span
                                                class="ml-2 text-sm text-gray-600"
                                                >Publish</span
                                            >
                                        </label>
                                    </div>
                                    <h3
                                        class="col-span-3 font-semibold text-gray-800 underline text-lg"
                                    >
                                        Responses
                                    </h3>
                                    <ul class="col-span-3 space-y-3">
                                        <li
                                            v-for="(
                                                answer, index
                                            ) in form.answers"
                                            :key="index"
                                        >
                                            <InputLabel
                                                :for="index"
                                                :value="`Response ${index + 1}`"
                                            />

                                            <div
                                                class="mt-1 flex rounded-md shadow-sm"
                                            >
                                                <div
                                                    class="relative flex flex-grow items-stretch focus-within:z-10"
                                                >
                                                    <TextInput
                                                        :id="index"
                                                        type="text"
                                                        class="block w-full rounded-none rounded-l-md border-gray-300 sm:text-sm"
                                                        v-model="
                                                            form.answers[index]
                                                                .title
                                                        "
                                                        autocomplete="off"
                                                    />
                                                </div>
                                                <button
                                                    type="button"
                                                    @click="deleteItem(index)"
                                                    class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-red-300 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500"
                                                >
                                                    <svg
                                                        class="w-5 h-5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        ></path>
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            </div>

                                            <TextInput
                                                type="hidden"
                                                class="mt-1 block w-full"
                                                v-model="form.answers[index].id"
                                                autocomplete="off"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.answers[index]
                                                        .title
                                                "
                                                v-if="hasError('title', index)"
                                            />
                                        </li>
                                        <li>
                                            <button
                                                type="button"
                                                @click="addItem()"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Add Item
                                            </button>
                                        </li>
                                    </ul>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.answers"
                                        v-if="form.errors.answers !== undefined"
                                    />
                                    <div class="col-span-3">
                                        <input
                                            type="file"
                                            @input="
                                                form.imagePath =
                                                    $event.target.files[0]
                                            "
                                            @change="
                                                (e) => {
                                                    previewImage(e);
                                                    form.clearErrors();
                                                }
                                            "
                                        />
                                        <progress
                                            v-if="form.progress"
                                            :value="form.progress.percentage"
                                            max="100"
                                            class="ml-4"
                                        >
                                            {{ form.progress.percentage }}%
                                        </progress>
                                        <img
                                            v-if="url"
                                            :src="url"
                                            class="w-48 h-auto mt-4"
                                        />
                                        <div
                                            v-if="form.errors.imagePath"
                                            class="mt-2 text-red-600 text-sm"
                                        >
                                            {{ form.errors.imagePath }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                            >
                                <PrimaryButton
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Save
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed } from "vue";

const form = useForm({
    title: null,
    publish: true,
    answers: [],
    imagePath: null,
    closed: 0,
});

const submit = () => {
    form.post(route("polls.store"));
};

const hasError = (fieldname, index) => {
    return (
        form.errors.answers !== undefined &&
        form.errors.answers[index] !== undefined &&
        form.errors.answers[index][fieldname] !== undefined
    );
};

const addItem = () => {
    form.clearErrors();
    form.answers.push({
        id: null,
        title: "Enter your response",
    });
};

const deleteItem = (index) => {
    form.answers.splice(index, 1);
};
</script>

<script>
export default {
    data() {
        return {
            url: "",
        };
    },
    methods: {
        previewImage: function (e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
            return this.url;
        },
    },
};
</script>
