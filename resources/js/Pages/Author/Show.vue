<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    author: Object,
    articles: Array,
});

const getAverageRating = (ratings) => {
    if (!ratings || ratings.length === 0) return 0;
    const sum = ratings.reduce((acc, curr) => acc + curr.score, 0);
    return (sum / ratings.length).toFixed(1);
};
</script>

<template>
    <Head :title="author.name + '\'s Profile'" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-16 pb-12 selection:bg-indigo-500 selection:text-white">
        <!-- Navigation Bar (Simple back link) -->
        <div class="fixed top-0 inset-x-0 h-16 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 z-50 flex items-center px-4 sm:px-6 lg:px-8">
            <Link :href="route('home')" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Discover
            </Link>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Author Profile Header -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-lg overflow-hidden mb-12">
                <div class="px-8 py-12 md:py-16 flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8 text-center md:text-left">
                    <div class="flex-shrink-0 h-32 w-32 relative">
                        <img v-if="author.avatar" :src="'/storage/' + author.avatar" class="h-32 w-32 rounded-full object-cover shadow-2xl border-4 border-white/20" />
                        <div v-else class="h-32 w-32 rounded-full bg-white/20 flex items-center justify-center text-white text-5xl font-bold shadow-2xl border-4 border-white/20">
                            {{ author.name.charAt(0) }}
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white">{{ author.name }}</h1>
                        <p class="mt-4 text-indigo-100 text-lg max-w-2xl">{{ author.bio || 'This author has not written a bio yet.' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Articles by {{ author.name }}</h2>
                
                <div v-if="articles.length === 0" class="text-center py-24 text-gray-500 dark:text-gray-400 text-lg">
                    No published articles yet.
                </div>
                
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <article v-for="article in articles" :key="article.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-gray-100 dark:border-gray-700 group">
                        <Link :href="route('articles.show', article.id)" class="block overflow-hidden relative pb-[56%]">
                            <img v-if="article.banner_url" :src="article.banner_url" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500" alt="Banner">
                            <div v-else class="absolute inset-0 w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-500">
                                <span class="text-white text-4xl font-bold opacity-30">{{ article.title.charAt(0) }}</span>
                            </div>
                        </Link>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex gap-2 flex-wrap mb-3">
                                <span v-for="g in article.genres" :key="g.id" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                    {{ g.name }}
                                </span>
                            </div>
                            
                            <Link :href="route('articles.show', article.id)" class="flex-grow">
                                <h2 class="text-xl font-bold leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 mb-2">
                                    {{ article.title }}
                                </h2>
                            </Link>
                            
                            <div class="mt-6 flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4">
                                <div class="flex items-center space-x-3">
                                    <!-- Author info is not strictly necessary here since it's the author's page, but good for consistency -->
                                    <div class="flex items-center space-x-2">
                                        <img v-if="author.avatar" :src="'/storage/' + author.avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        <div v-else class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-300">
                                            {{ author.name.charAt(0) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                            {{ author.name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-4">
                                    <span class="flex items-center" title="Comments">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        {{ article.comments?.length || 0 }}
                                    </span>
                                    <span class="flex items-center" title="Average Rating">
                                        <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        {{ getAverageRating(article.ratings) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

        </div>
    </div>
</template>
