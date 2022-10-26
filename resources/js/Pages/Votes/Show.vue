<template>
    <Head :title="`Vote : ${poll.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vote : {{ poll.title }}
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="p-4">
                            <div class="w-full flex justify-center my-6">
                                <img
                                    v-if="url"
                                    :src="url"
                                    class="w-56 h-auto mt-6"
                                />
                            </div>
                            <h2
                                class="font-semibold text-gray-800 text-lg"
                                v-text="poll.title"
                            ></h2>

                            <ul class="mt-4 space-y-2">
                                <li
                                    v-for="answer in poll.answers"
                                    :key="answer.id"
                                    class="rounded-full px-3 py-2 border border-gray-200 cursor-pointer"
                                    :class="
                                        hasVotedForAnswer(answer.id)
                                            ? 'bg-indigo-600 text-white'
                                            : 'bg-white hover:bg-gray-100 text-gray-600'
                                    "
                                    @click="submit(answer.id)"
                                >
                                    <span v-text="answer.title"></span>
                                </li>
                            </ul>
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

const props = defineProps({
    poll: Object,
    auth: Object,
});

const form = useForm({
    answer_id: null,
});

const hasVotedForAnswer = (answerId) => {
    return (
        props.poll.votes.filter((vote) => {
            return (
                vote.user_id === props.auth.user.id &&
                vote.answer_id === answerId
            );
        }).length > 0
    );
};

const submit = (answerId) => {
    form.answer_id = answerId;

    form.patch(route("votes.update", props.poll.id));
};
</script>

<script>
export default {
    data() {
        return {
            url: this.poll.imagePath != '' ? window.location.origin + "/storage/" + this.poll.imagePath : false,
        };
    },
};
</script>
