<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    },
    articles: Object,
    genres: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const genre = ref(props.filters?.genre || '');

const handleSearch = () => {
    router.get(route('home'), { search: search.value, genre: genre.value }, { preserveState: true, replace: true });
};

watch([search, genre], () => {
    handleSearch();
});

const getAverageRating = (ratings) => {
    if (!ratings || ratings.length === 0) return 0;
    const sum = ratings.reduce((acc, curr) => acc + curr.score, 0);
    return (sum / ratings.length).toFixed(1);
};
</script>

<template>
    <Head title="MindScribe - Discover Articles" />

    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 selection:bg-indigo-500 selection:text-white">
        <!-- Navigation -->
        <nav class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-3 text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span>MindScribe</span>
                    </div>
                    <div v-if="canLogin" class="flex space-x-4">
                        <Link v-if="$page.props.auth.user" :href="route('articles.index')" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            My Articles
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">Log in</Link>
                            <Link v-if="canRegister" :href="route('register')" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">Register</Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header & Search -->
            <div class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight">Discover Amazing Content</h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Read the latest articles from talented authors.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <input 
                        v-model="search" 
                        type="text" 
                        placeholder="Search articles..." 
                        class="px-4 py-2 w-full sm:w-64 rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    >
                    <select v-model="genre" class="px-4 py-2 w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">All Genres</option>
                        <option v-for="g in genres" :key="g.id" :value="g.slug">{{ g.name }}</option>
                    </select>
                </div>
            </div>

            <!-- Grid -->
            <div v-if="articles.data.length === 0" class="text-center py-24 text-gray-500 dark:text-gray-400 text-lg">
                No articles found matching your criteria.
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <article v-for="article in articles.data" :key="article.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-gray-100 dark:border-gray-700 group">
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
                                <Link :href="route('author.show', article.author.id)" class="flex items-center space-x-2 group/author">
                                    <img v-if="article.author.avatar" :src="'/storage/' + article.author.avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                    <div v-else class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-300">
                                        {{ article.author.name.charAt(0) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover/author:text-indigo-600 dark:group-hover/author:text-indigo-400 transition-colors">
                                        {{ article.author.name }}
                                    </span>
                                </Link>
                            </div>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-4">
                                <span class="flex items-center" title="Comments">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    {{ article.comments.length }}
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
            
            <!-- Pagination -->
            <div v-if="articles.links && articles.links.length > 3" class="mt-12 flex justify-center flex-wrap gap-2">
                <Link v-for="link in articles.links" :key="link.label" :href="link.url || '#'" :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    link.active ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700',
                    !link.url ? 'opacity-50 cursor-not-allowed' : ''
                ]" v-html="link.label"></Link>
            </div>
        </main>
    </div>
</template>
