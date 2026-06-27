# MindScribe Git Workflow Guide

Welcome to the MindScribe project team! To ensure a smooth Agile Scrum execution without merge conflicts, please follow this standardized Git workflow for your daily tasks.

## 1. Setup & Sync (Start of your day / task)

Before starting any new work, always ensure you have the latest code from the main repository.

```bash
git checkout main
git pull origin main
```

## 2. Create a Feature Branch

Create a new branch for the specific User Story or task you are working on. Use a descriptive name linking to the Epic or US ID.

```bash
git checkout -b feature/US001-user-registration
```
*(Replace `US001-user-registration` with your specific task ID/name)*

## 3. Work and Commit Frequently

As you complete logical chunks of work, stage and commit your changes.

**To check what files have changed:**
```bash
git status
```

**To stage specific files (Recommended):**
```bash
git add path/to/your/file.php
git add path/to/another/file.vue
```

**To stage all changed files:**
```bash
git add .
```

**To commit with a clear, descriptive message:**
```bash
git commit -m "feat: implement user registration validation for US001"
```

*Commit Message Guidelines:*
- `feat:` for new features
- `fix:` for bug fixes
- `style:` for CSS/UI changes
- `refactor:` for code restructuring without changing behavior
- `test:` for QA tests

## 4. Push Your Branch

Once your task is complete and committed locally, push your branch to the remote repository.

```bash
git push -u origin feature/US001-user-registration
```

## 5. Open a Pull Request (PR)

1. Go to the project's GitHub/GitLab repository page.
2. Click **"Compare & pull request"** next to your recently pushed branch.
3. Write a clear description of what the PR accomplishes and link it to the Jira/Scrum board ticket.
4. Request a review from the **Scrum Master** or a peer developer.

## 6. Handling Merge Conflicts (If they happen)

If someone else merged code into `main` that conflicts with your branch, you will need to rebase or merge `main` into your branch.

```bash
git checkout main
git pull origin main
git checkout feature/US001-user-registration
git merge main
```
Resolve any conflicts in your editor, then:
```bash
git add .
git commit -m "chore: merge main and resolve conflicts"
git push origin feature/US001-user-registration
```

## Daily Summary
Copy-paste flow for daily routine:
```bash
git checkout main
git pull origin main
git checkout -b feature/my-new-task
# ... work on code ...
git add .
git commit -m "feat: my changes"
git push -u origin feature/my-new-task
```
