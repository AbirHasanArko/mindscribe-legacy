<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    article: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const articleContent = computed(() => {
    try {
        const json = JSON.parse(props.article.content_json);
        // TipTap JSON to HTML would typically require the TipTap library to render
        // Since we stored JSON from editor.getJSON(), we should render it using TipTap's generateHTML or use editor-content.
        // For simplicity and since TipTap Vue 3 is available, we will render it dynamically below in template using EditorContent
        return json;
    } catch (e) {
        return null;
    }
});

const commentForm = useForm({
    content: ''
});

const submitComment = () => {
    commentForm.post(route('comments.store', props.article.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset('content')
    });
};

const ratingForm = useForm({
    score: null
});

const rate = (score) => {
    if (!user.value) return;
    ratingForm.score = score;
    ratingForm.post(route('ratings.store', props.article.id), {
        preserveScroll: true
    });
};

const userRating = computed(() => {
    if (!user.value) return 0;
    const rating = props.article.ratings.find(r => r.user_id === user.value.id);
    return rating ? rating.score : 0;
});

const averageRating = computed(() => {
    if (props.article.ratings.length === 0) return 0;
    const sum = props.article.ratings.reduce((acc, r) => acc + r.score, 0);
    return (sum / props.article.ratings.length).toFixed(1);
});
</script>

<script>
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import TipTapLink from '@tiptap/extension-link';

export default {
    components: {
        EditorContent,
    },
    data() {
        return {
            editor: null,
        }
    },
    mounted() {
        let content = '';
        try {
            content = JSON.parse(this.article.content_json);
        } catch(e) {
            content = this.article.content_json;
        }

        this.editor = new Editor({
            content: content,
            editable: false,
            extensions: [StarterKit, Image, TipTapLink],
            editorProps: {
                attributes: {
                    class: 'prose dark:prose-invert prose-lg md:prose-xl mx-auto focus:outline-none',
                },
            },
        });
    },
    beforeUnmount() {
        if (this.editor) {
            this.editor.destroy();
        }
    }
}
</script>

<template>
    <Head :title="article.title" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-16 pb-12 selection:bg-indigo-500 selection:text-white">
        <!-- Navigation Bar (Simple back link) -->
        <div class="fixed top-0 inset-x-0 h-16 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 z-50 flex items-center px-4 sm:px-6 lg:px-8">
            <Link :href="route('home')" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Discover
            </Link>
        </div>

        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <!-- Header -->
            <header class="mb-10 text-center">
                <div class="flex justify-center gap-2 flex-wrap mb-4">
                    <span v-for="g in article.genres" :key="g.id" class="px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                        {{ g.name }}
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6">
                    {{ article.title }}
                </h1>
                
                <div class="flex items-center justify-center space-x-4 text-gray-600 dark:text-gray-400">
                    <Link :href="route('author.show', article.author.id)" class="flex items-center space-x-2 group">
                        <img v-if="article.author.avatar" :src="'/storage/' + article.author.avatar" class="w-10 h-10 rounded-full object-cover shadow-sm">
                        <div v-else class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-500 dark:text-gray-300">
                            {{ article.author.name.charAt(0) }}
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                            {{ article.author.name }}
                        </span>
                    </Link>
                    <span>•</span>
                    <time>{{ new Date(article.updated_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}</time>
                </div>
            </header>

            <!-- Banner -->
            <div v-if="article.banner_url" class="mb-12 rounded-2xl overflow-hidden shadow-lg aspect-video relative">
                <img :src="article.banner_url" class="absolute inset-0 w-full h-full object-cover" alt="Banner">
            </div>

            <!-- Content -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 md:p-12 mb-12">
                <editor-content v-if="editor" :editor="editor" />
            </div>
            
            <!-- Interactions: Ratings -->
            <div class="border-t border-b border-gray-200 dark:border-gray-700 py-8 mb-12 flex flex-col items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Rate this article</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Average Rating: {{ averageRating }} / 5 ({{ article.ratings.length }} votes)</p>
                <div class="flex space-x-2">
                    <button 
                        v-for="star in 5" 
                        :key="star"
                        @click="rate(star)"
                        :disabled="!user"
                        :class="[
                            'p-2 rounded-full transition transform hover:scale-110 focus:outline-none',
                            (userRating >= star) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600',
                            !user ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'
                        ]"
                    >
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </button>
                </div>
                <p v-if="!user" class="mt-4 text-sm text-gray-500"><Link :href="route('login')" class="text-indigo-600 underline">Log in</Link> to rate this article.</p>
            </div>

            <!-- Comments Section -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Comments ({{ article.comments.length }})</h3>
                
                <div v-if="user" class="mb-10 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <form @submit.prevent="submitComment">
                        <textarea 
                            v-model="commentForm.content"
                            rows="3" 
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Share your thoughts..."
                            required
                        ></textarea>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" :disabled="commentForm.processing" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
                <div v-else class="mb-10 bg-gray-100 dark:bg-gray-800 rounded-xl p-6 text-center shadow-sm">
                    <p class="text-gray-600 dark:text-gray-400">Please <Link :href="route('login')" class="text-indigo-600 font-semibold hover:underline">log in</Link> to join the discussion.</p>
                </div>

                <div class="space-y-6">
                    <div v-for="comment in article.comments" :key="comment.id" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-3">
                            <img v-if="comment.user.avatar" :src="'/storage/' + comment.user.avatar" class="w-10 h-10 rounded-full object-cover">
                            <div v-else class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold">
                                {{ comment.user.name.charAt(0) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">{{ comment.user.name }}</div>
                                <div class="text-xs text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</div>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ comment.content }}</p>
                    </div>
                </div>
            </div>

        </article>
    </div>
</template>
