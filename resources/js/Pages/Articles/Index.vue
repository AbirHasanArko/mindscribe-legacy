<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    articles: Array,
});
</script>

<template>
    <Head title="My Articles" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    My Articles
                </h2>
                <Link :href="route('articles.create')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    New Article
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="articles.length === 0" class="text-center py-8 text-gray-500">
                            You haven't written any articles yet.
                        </div>
                        <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li v-for="article in articles" :key="article.id" class="py-4 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-bold">
                                        <Link :href="route('articles.edit', article.id)" class="hover:text-indigo-500 transition">
                                            {{ article.title || 'Untitled Article' }}
                                        </Link>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Status: <span class="uppercase text-xs font-semibold" :class="article.status === 'published' ? 'text-green-500' : 'text-yellow-500'">{{ article.status }}</span>
                                        • Last updated {{ new Date(article.updated_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <div class="flex space-x-3">
                                    <Link v-if="article.status === 'published'" :href="route('articles.show', article.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View</Link>
                                    <Link :href="route('articles.edit', article.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Edit</Link>
                                    <Link :href="route('articles.destroy', article.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</Link>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
