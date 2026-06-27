<p align="center">
  <img src="public/logo.svg" alt="MindScribe Logo" width="100">
</p>

<h1 align="center">MindScribe</h1>

<p align="center">
  <strong>A premium, dynamic content authoring and discovery platform.</strong><br>
  Built with Laravel 13, Vue.js 3 (Inertia), Tailwind CSS, and TipTap.
</p>

---

## 🚀 Features

- **Robust Content Authoring (TipTap):** A block-style rich text editor with in-body image embeds, headings, and dynamic JSON output rendering.
- **Discovery Feed (Photocards):** A beautiful grid layout displaying articles with cover banners, author details, genres, and interactive metrics (comments & ratings).
- **Author Profiles:** Stunning public profile pages for authors to showcase their published works.
- **Authentication:** Secure user registration, login, and session management powered by Laravel Breeze.
- **Light & Dark Mode:** Deep integration with Tailwind's dark mode, defaulting to system preference.
- **Search & Filtering:** Dynamic query searching and genre tagging/filtering for content discovery.
- **SQLite Optimization:** Pre-configured with SQLite using Write-Ahead Logging (WAL) for high performance.

## 🛠️ Tech Stack

- **Backend:** [Laravel 13](https://laravel.com/) (PHP)
- **Frontend:** [Vue.js 3](https://vuejs.org/) (Composition API) + [Inertia.js](https://inertiajs.com/)
- **Styling:** [Tailwind CSS 3](https://tailwindcss.com/)
- **Editor:** [TipTap](https://tiptap.dev/) (ProseMirror-based headless editor)
- **Database:** SQLite (WAL mode)

---

## ⚙️ Local Development Setup

Follow these steps to get your local environment up and running.

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (v18+) & NPM

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AbirHasanArko/mindscribe-legacy.git
   cd mindscribe-legacy
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the example `.env` file and generate the application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Storage Link:**
   Ensure public disk files (like uploaded images) are accessible.
   ```bash
   php artisan storage:link
   ```

6. **Database Migration & Seeding:**
   Run the migrations to create the SQLite database and tables.
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Frontend Assets:**
   ```bash
   npm run build
   ```
   *(Or `npm run dev` to watch for changes during active development)*

8. **Start the Local Server:**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

---

## 📈 Git & Agile Workflow

This project strictly adheres to an Agile Scrum methodology. All contributors must follow the branching and commit protocols defined in the project structure.

- **Branch Naming:** `feature/[ISSUE-ID]-[descriptor]` (e.g., `feature/MS-5-session-fix`)
- **Commit Messages:** Must follow the Jira Smart Commit format:
  ```bash
  [ISSUE-ID]: [Imperative Verb] [Description] #done
  ```
  *Example:* `MS-4: implement User Registration and Sign Up routing #done`

For a complete list of roles, user stories, and specific Git commands, please refer to the [git_commands_by_role.md](git_commands_by_role.md) file included in this repository.

---

## 📜 License

MindScribe is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
