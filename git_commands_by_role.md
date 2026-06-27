# Git Workflow Commands by Role

This document outlines the Git branching and commit commands for each team role, based on the Sprint Schedule and User Stories defined for MindScribe. All commands strictly adhere to the Jira Smart Commit protocol and Branching naming conventions.

---

## 🌿 Core Git Workflow Guide (Full Lifecycle)

We strictly adhere to a Feature Branch Workflow. **Do not commit directly to the `main` branch.**

Repository URL: `https://github.com/AbirHasanArko/MindScribe` (or `mindscribe-legacy`)

### 1. Clone the Repository
If you are starting fresh, clone the repository to your local machine and navigate into the project directory:
```bash
git clone https://github.com/AbirHasanArko/MindScribe.git
cd MindScribe
```

### 2. Pull the Latest Changes
If you already have the repository cloned, always ensure your local `main` branch is fully up to date before starting new work:
```bash
git checkout main
git pull origin main
```

### 3. Create a Feature Branch
Create and switch to a new branch using the exact Jira Issue ID and a short descriptor:
```bash
git checkout -b feature/MS-5-session-fix
```

### 4. Stage ONLY Necessary Files
Avoid using `git add .` as it can stage unintended configuration files. Explicitly target the files you modified or created for this specific task:
```bash
git add app/Http/Controllers/Auth/AuthenticatedSessionController.php routes/auth.php
```

### 5. Write a Jira Smart Commit
Your commit message must begin with the Issue ID and an imperative verb. Use a multi-line commit to explain the details. Append `#done` if the task is complete.
```bash
git commit -m "MS-5: configure User Login authentication logic #done

- Handled secure login sessions using Laravel Breeze
- Added rate limiting to prevent brute force attacks"
```

### 6. Push Your Branch
Push your newly created feature branch to the remote repository so it is backed up and visible to the team:
```bash
git push -u origin feature/MS-5-session-fix
```

### 7. Create and Merge a Pull Request (PR)
1. Go to your repository on GitHub.
2. Click **Compare & pull request** next to your recently pushed branch.
3. Ensure the base branch is `main` and the compare branch is `feature/MS-5-session-fix`.
4. Add a description outlining your changes and request a review from QA or the Scrum Master.
5. Once approved, click **Merge pull request** to merge your code into `main`.

### 8. Cleanup After Merging
After your branch is successfully merged on GitHub, delete it locally and remotely to keep the repository clean:
```bash
git checkout main
git pull origin main
git branch -d feature/MS-5-session-fix
git push origin --delete feature/MS-5-session-fix
```

---

## Role-Specific Command Matrix


## 👨‍💻 Back-end Developer
**Focus:** Laravel app logic, Eloquent models, authentication, SQLite database, storage, API endpoints.

### Sprint 1
**User Story:** MS-4: User Registration and Sign Up
```bash
git checkout -b feature/MS-4-user-registration
git add app/Http/Controllers/Auth/RegisteredUserController.php routes/auth.php database/factories/UserFactory.php
git commit -m "MS-4: implement User Registration and Sign Up routing #done

- Built RegisteredUserController for account creation
- Implemented robust email/password validation rules
- Mapped /register route in auth.php
- Generated baseline UserFactory for seeding"
```

**User Story:** MS-5: User Login & Session Management
```bash
git checkout -b feature/MS-5-session-management
git add app/Http/Controllers/Auth/AuthenticatedSessionController.php routes/auth.php config/session.php
git commit -m "MS-5: configure User Login & Session Management authentication logic #done

- Handled secure login sessions using Laravel Breeze
- Added rate limiting to prevent brute force attacks
- Implemented session invalidation on logout
- Verified CSRF token protection on web routes"
```

### Sprint 2
**User Story:** MS-10: Media Uploads and Image Embeds
```bash
git checkout -b feature/MS-10-media-uploads
git add app/Http/Controllers/ArticleController.php routes/web.php config/filesystems.php
git commit -m "MS-10: build asynchronous media upload controllers and storage scripting #done

- Created API endpoint for handling multipart/form-data images
- Configured local storage disks and symlinks (storage:link)
- Implemented file size and mime-type validation logic
- Generated unique filenames to prevent storage collisions"
```

### Sprint 3
**User Story:** MS-12: Genre Tag Mapping
```bash
git checkout -b feature/MS-12-genre-mapping
git add app/Models/Genre.php app/Models/Article.php database/migrations/
git commit -m "MS-12: declare Eloquent models and cascading pivot relational rules for genres #done

- Created Genre model and migration table
- Built many-to-many article_genre pivot table
- Defined belongsToMany relationships on Article and Genre models
- Handled cascading deletes for orphaned pivot rows"
```

**User Story:** MS-13: Dynamic Query Search & Genre Filtering
```bash
git checkout -b feature/MS-13-query-search
git add app/Models/Article.php app/Http/Controllers/PublicController.php
git commit -m "MS-13: build dynamic local scope database pipeline query search #done

- Added scopeFilter to Article model for robust querying
- Implemented WHERE LIKE logic for title and excerpt searches
- Built relationship querying (whereHas) for genre-based filtering
- Integrated pagination seamlessly with search parameters"
```

### Sprint 4
**User Story:** MS-15: Interactive Article Comments Engine
```bash
git checkout -b feature/MS-15-comments-engine
git add app/Models/Comment.php app/Http/Controllers/CommentController.php database/migrations/ routes/web.php
git commit -m "MS-15: implement interactive article comments engine backend logic #done

- Created Comment model and defined polymorphic or direct relationships
- Built CommentController for store, update, and destroy methods
- Validated incoming comment string lengths and content
- Eager-loaded comments recursively on article fetch"
```

**User Story:** MS-16: Star Metric Article Rating System
```bash
git checkout -b feature/MS-16-star-rating
git add app/Models/Rating.php app/Http/Controllers/RatingController.php database/migrations/ routes/web.php
git commit -m "MS-16: build star metric article rating system database tables and controllers #done

- Generated Rating model tracking user_id, article_id, and score
- Built upsert logic in RatingController to handle vote changes
- Implemented average score calculation aggregation methods
- Ensured a strict 1-to-5 validation bounds rule"
```

---

## 🎨 Front-end Developer
**Focus:** Blade/Inertia views, Tailwind styling, Editor.js/TipTap integration, responsive & accessible UI.

### Sprint 1
**User Story:** MS-6: My Profile Settings & Theme Toggle
```bash
git checkout -b feature/MS-6-profile-settings
git add resources/js/Pages/Profile/ resources/js/Components/ resources/views/app.blade.php
git commit -m "MS-6: build profile settings and Tailwind dark mode theme toggle UI #done

- Refactored Profile/Edit.vue with customized Tailwind components
- Implemented local storage watcher for Dark Mode preferences
- Ensured seamless theme transitioning (glassmorphism logic)
- Updated SVG iconography to scale accurately per theme"
```

**User Story:** MS-7: Photocard Grid Discovery Feed Dashboard
```bash
git checkout -b feature/MS-7-discovery-feed
git add resources/js/Pages/Welcome.vue
git commit -m "MS-7: implement photocard grid discovery feed dashboard UI #done

- Designed premium article photocard layout on Welcome.vue
- Integrated smooth group-hover scaling and transition effects
- Built dynamic grid rendering (1-column mobile to 4-column desktop)
- Bound Inertia paginator links to grid footer"
```

### Sprint 2
**User Story:** MS-8: Article Editor Workspace (Draft Mode)
```bash
git checkout -b feature/MS-8-editor-workspace
git add resources/js/Pages/Articles/Editor.vue routes/web.php
git commit -m "MS-8: build article editor workspace and draft mode layout #done

- Created standalone Editor.vue component outside of standard layout
- Built floating toolbar for block manipulation
- Styled input fields for title and banner attachments
- Implemented reactive autosave draft indicator UI"
```

**User Story:** MS-9: Advanced Text Editor Block Engine
```bash
git checkout -b feature/MS-9-text-editor
git add resources/js/Pages/Articles/Editor.vue package.json package-lock.json
git commit -m "MS-9: integrate interactive client JS blocks for advanced text editor engine #done

- Installed and initialized TipTap headless editor framework
- Bound Document, Paragraph, Text, and Heading nodes
- Registered custom Image block extension with upload handlers
- Configured raw JSON state extraction for the database payload"
```

### Sprint 3
**User Story:** MS-11: Author Profile Page View
```bash
git checkout -b feature/MS-11-author-profile
git add resources/js/Pages/Author/Show.vue app/Http/Controllers/ProfileController.php
git commit -m "MS-11: build author profile page view and layout #done

- Designed premium gradient header layout for Author identity
- Rendered author avatar, bio, and dynamic stat aggregates
- Reused the photocard grid component for the author's specific articles
- Handled empty states gracefully when no articles exist"
```

**User Story:** MS-14: Premium Typography Reader Layout
```bash
git checkout -b feature/MS-14-premium-typography
git add resources/js/Pages/Articles/Show.vue tailwind.config.js
git commit -m "MS-14: implement premium typography reader layout #done

- Built Article/Show.vue utilizing Tailwind Typography plugin (@tailwindcss/typography)
- Styled prose classes dynamically matching standard or dark themes
- Implemented sticky table-of-contents sidebar navigation
- Optimized line-height and max-width for maximum readability"
```

---

## 🧪 Full-stack QA
**Focus:** Test planning & execution, security/regression testing, automated assertions, defect tracking.

*QA typically commits automated test suites (e.g., PHPUnit, Cypress, Dusk) and defect fixes linked to the main user stories.*

**Automated Back-end Integration & Unit Assertions (Example for Sprint 1)**
```bash
git checkout -b test/MS-4-auth-assertions
git add tests/Feature/Auth/
git commit -m "MS-4: write comprehensive automated back-end integration tests for authentication #done

- Implemented PHPUnit test case for valid user registration
- Tested failure constraints on duplicate email entries
- Simulated password length boundary exceptions
- Verified proper database seeding configurations"
```

**Executing Layout Matrix Multi-browser Audits & Fixes (Example for Sprint 2)**
```bash
git checkout -b bugfix/MS-8-editor-browser-audit
git add resources/js/Pages/Articles/Editor.vue resources/css/app.css
git commit -m "MS-8: fix layout matrix inconsistencies in editor workspace #done

- Resolved TipTap cursor overflow issue on Firefox
- Fixed z-index layering bug with floating toolbars on mobile Safari
- Adjusted responsive padding for edge-case tablet resolutions
- Ensured semantic HTML structural integrity"
```

---

## 📊 Scrum Master
**Focus:** Facilitating ceremonies, Jira board maintenance, velocity tracking.

*Scrum Masters do not typically write application code, but if they manage repository documentation (like updating `README.md` or Agile workflows), they should also use Smart Commits.*

```bash
git checkout -b task/MS-0-agile-documentation
git add README.md git_commands_by_role.md Agile_Scrum_Project_MindScribe.xlsx
git commit -m "MS-0: update project documentation with Sprint Schedule and Scope breakdown #done

- Outlined explicit Back-end, Front-end, and QA responsibilities
- Listed full Sprint timetable and associated story points
- Detailed Git Commit and Branching procedural rules
- Embedded quick-start local environment guide"
```
