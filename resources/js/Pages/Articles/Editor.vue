<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';

const props = defineProps({
    article: Object,
    genres: Array,
});

const title = ref(props.article.title || '');
const banner_url = ref(props.article.banner_url || '');
const status = ref(props.article.status || 'draft');
const selectedGenres = ref(props.article.genres ? props.article.genres.map(g => g.id) : []);
const isSaving = ref(false);
const lastSaved = ref(null);

let editor = null;
let autosaveTimer = null;

onMounted(() => {
    let initialContent = '';
    if (props.article.content_json) {
        try {
            initialContent = JSON.parse(props.article.content_json);
        } catch (e) {
            initialContent = props.article.content_json;
        }
    }

    editor = new Editor({
        content: initialContent,
        extensions: [
            StarterKit,
            Image,
            Link,
        ],
        editorProps: {
            attributes: {
                class: 'prose dark:prose-invert prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none min-h-[500px] py-4',
            },
        },
    });

    // Auto-save every 30 seconds
    autosaveTimer = setInterval(() => {
        saveArticle(false);
    }, 30000);
});

onUnmounted(() => {
    if (editor) {
        editor.destroy();
    }
    if (autosaveTimer) {
        clearInterval(autosaveTimer);
    }
});

const bannerFile = ref(null);

const handleBannerChange = (e) => {
    if (e.target.files.length > 0) {
        bannerFile.value = e.target.files[0];
        // Preview
        banner_url.value = URL.createObjectURL(bannerFile.value);
    }
};

const saveArticle = (manual = true) => {
    if (!title.value && !editor.getText().trim()) return;

    isSaving.value = true;
    
    const formData = new FormData();
    formData.append('title', title.value || 'Untitled Article');
    formData.append('content_json', JSON.stringify(editor.getJSON()));
    formData.append('status', status.value);
    
    selectedGenres.value.forEach(g => {
        formData.append('genres[]', g);
    });

    if (bannerFile.value) {
        formData.append('banner', bannerFile.value);
    }

    if (props.article.id) {
        formData.append('_method', 'put');
    }

    const url = props.article.id 
        ? route('articles.update', props.article.id)
        : route('articles.store');

    axios.post(url, formData, { headers: { 'Accept': 'application/json' } })
        .then(response => {
            isSaving.value = false;
            lastSaved.value = new Date();
            if (!props.article.id && response.data.article) {
                // Redirect to edit mode without full page reload if it's a new article
                router.visit(route('articles.edit', response.data.article.id), { preserveScroll: true, preserveState: true });
            }
        })
        .catch(error => {
            isSaving.value = false;
            console.error('Save failed', error);
        });
};

const bodyImageInput = ref(null);

const addImage = () => {
    if (bodyImageInput.value) {
        bodyImageInput.value.click();
    }
};

const handleBodyImageUpload = async (e) => {
    if (e.target.files.length === 0) return;
    
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append('image', file);

    try {
        const response = await axios.post(route('articles.upload-image'), formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        if (response.data.url) {
            editor.chain().focus().setImage({ src: response.data.url }).run();
        }
    } catch (error) {
        console.error('Image upload failed', error);
        alert('Failed to upload image. Please try again.');
    }
    
    // Reset input
    e.target.value = '';
};

const publish = () => {
    status.value = 'published';
    saveArticle(true);
};
</script>

<template>
    <Head :title="title || 'New Article'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between items-center">
                    <input 
                        v-model="title" 
                        type="text" 
                        placeholder="Article Title..." 
                        class="text-2xl font-bold bg-transparent border-none focus:ring-0 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-600 w-full"
                    >
                    <div class="flex items-center space-x-4 flex-shrink-0">
                        <span v-if="isSaving" class="text-sm text-gray-500">Saving...</span>
                        <span v-else-if="lastSaved" class="text-sm text-gray-500">Saved {{ lastSaved.toLocaleTimeString() }}</span>
                        
                        <button @click="saveArticle(true)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Save Draft
                        </button>
                        <button @click="publish" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Publish
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image (Optional)</label>
                    <div v-if="banner_url" class="mb-4 relative w-full h-48 rounded overflow-hidden">
                        <img :src="banner_url.startsWith('http') || banner_url.startsWith('blob') || banner_url.startsWith('/storage') ? banner_url : '/storage/' + banner_url" class="object-cover w-full h-full">
                    </div>
                    <input 
                        @change="handleBannerChange"
                        type="file" 
                        accept="image/*"
                        class="text-sm bg-transparent border-none focus:ring-0 text-gray-600 dark:text-gray-400 placeholder-gray-400 dark:placeholder-gray-600 w-full file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300"
                    >
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <!-- Editor Toolbar (Simple) -->
                    <div v-if="editor" class="border-b border-gray-200 dark:border-gray-700 p-2 flex space-x-2 overflow-x-auto bg-gray-50 dark:bg-gray-900 rounded-t-lg">
                        <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('bold') }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Bold</button>
                        <button @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('italic') }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Italic</button>
                        <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('heading', { level: 2 }) }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">H2</button>
                        <button @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('heading', { level: 3 }) }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">H3</button>
                        <button @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('blockquote') }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Quote</button>
                        <button @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-gray-200 dark:bg-gray-700': editor.isActive('bulletList') }" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Bullet List</button>
                        <button @click="addImage" class="px-3 py-1 rounded text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Image</button>
                    </div>

                    <input 
                        type="file" 
                        ref="bodyImageInput" 
                        @change="handleBodyImageUpload" 
                        accept="image/*" 
                        class="hidden"
                    >

                    <!-- Genre Selection -->
                    <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-wrap gap-4 items-center">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Genres:</span>
                        <label v-for="genre in genres" :key="genre.id" class="inline-flex items-center">
                            <input type="checkbox" :value="genre.id" v-model="selectedGenres" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ genre.name }}</span>
                        </label>
                    </div>
                    
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <editor-content :editor="editor" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
