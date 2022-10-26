<template>
    <Head title="Polls" />

    <AuthenticatedLayout :status="status">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Polls ({{ polls.length }})
            </h2>

            <Link
                :href="route('polls.create')"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            >
                Add poll
            </Link>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div
                            class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                        >
                            <div
                                class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
                            >
                                <table
                                    class="min-w-full divide-y divide-gray-300"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                Question
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                                            >
                                                Answers
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                                            >
                                                Votes
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                                            >
                                                Published
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                                            >
                                                Closed
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900"
                                            >
                                                Creation
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                            >
                                                <span class="sr-only"
                                                    >Edit</span
                                                >
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 bg-white"
                                    >
                                        <tr
                                            v-for="poll in polls"
                                            :key="poll.id"
                                            class="bg-white even:bg-gray-50 hover:bg-gray-100"
                                        >
                                            <td
                                                class="truncate py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 max-w-[420px]"
                                            >
                                                {{ poll.title }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"
                                            >
                                                {{ poll.answers_count }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"
                                            >
                                                {{ poll.votes_count }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"
                                            >
                                                <span
                                                    class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                    :class="
                                                        poll.publish
                                                            ? 'text-green-800 bg-green-100'
                                                            : 'text-red-800 bg-red-100'
                                                    "
                                                    v-text="
                                                        poll.publish
                                                            ? 'Yes'
                                                            : 'No'
                                                    "
                                                ></span>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"
                                            >
                                                <span
                                                    class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                    :class="
                                                        poll.closed
                                                            ? 'text-red-800 bg-red-100'
                                                            : 'text-green-800 bg-green-100'
                                                    "
                                                    v-text="
                                                        poll.closed
                                                            ? 'Closed'
                                                            : 'Open'
                                                    "
                                                ></span>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right"
                                            >
                                                {{
                                                    moment(
                                                        new Date(
                                                            poll.created_at
                                                        )
                                                    ).format(
                                                        "dddd Do MMM YYYY A HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-4"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'polls.clear-votes',
                                                            poll
                                                        )
                                                    "
                                                    class="text-green-600 hover:text-green-900"
                                                    v-if="poll.votes_count"
                                                    >Clear votes<span
                                                        class="sr-only"
                                                        >,
                                                        {{ poll.title }}</span
                                                    ></Link
                                                >
                                                <Link
                                                    :href="
                                                        route(
                                                            'polls.edit',
                                                            poll
                                                        )
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    >Edit<span class="sr-only"
                                                        >,
                                                        {{ poll.title }}</span
                                                    ></Link
                                                >
                                                <button
                                                    type="button"
                                                    @click="destroy(poll)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Delete<span class="sr-only"
                                                        >,
                                                        {{ poll.title }}</span
                                                    >
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";

defineProps({
    polls: Array,
    status: String,
});

const form = useForm();

const destroy = (poll) => {
    if (confirm(`Are you sure you want to delete the "${poll.title}" poll?`)) {
        form.delete(route("polls.destroy", poll.id));
    }
};
</script>

<script>
import moment from "moment";

export default {
    methods: {
        moment: moment,
    },
};
</script>
