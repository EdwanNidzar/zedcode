# AI Deployment Context: Project Zedcore

## 1. Project Overview
- **Project Name:** zedcore
- **Description:**  Web app untuk pengajuan cuti dan handbook SOP
- **Primary Language/Framework:** Laravel 13
- **Package Manager:** composer, npm
- **Language Version:** Node 20, PHP 8.4, mysql 8.4

## 2. Application Configuration
- **Entry Point / Start Command:** `php artisan serve`
- **Exposed Port:** 8000
- **Build Steps (jika ada):** none
- **Environment Variables (.env):** 
  - APP_ENV=production
  - APP_DEBUG=false
  - APP_URL=localhost
  - DB_CONNECTION=mysql
  - DB_HOST=[IP_ADDRESS]
  - DB_PORT=3306
  - DB_DATABASE=zedcore
  - DB_USERNAME=root
  - DB_PASSWORD=[PASSWORD]

## 3. Deployment Target & Architecture
- **Infrastructure:** VPS Hostinger
- **Control Panel:** Easypanel
- **Containerization:** Docker
- **Repository Provider:** GitHub
- **CI/CD Tool:** GitHub Actions

## 4. Instructions for the AI Agent
Hello AI, based on the context provided above, please generate the following configuration files for my project:

### Task 1: Generate `Dockerfile`
- Create a highly optimized, production-ready `Dockerfile`.
- Use a lightweight base image (e.g., `-alpine` tags).
- Implement multi-stage builds if the framework requires a build step (e.g., for Next.js, React, or Go).
- Ensure dependency installation leverages Docker layer caching efficiently.
- Expose the correct port defined in the application configuration.
- Define the proper `CMD` or `ENTRYPOINT` to start the application.
- Provide a `.dockerignore` file recommendation.

### Task 2: Generate GitHub Actions CI/CD Workflow (`.github/workflows/deploy.yml`)
- The pipeline should trigger on a `push` to the `main` or `master` branch.
- **Job 1 (Test/Lint):** (Optional but recommended) Run basic setup and tests if applicable.
- **Job 2 (Deploy via Easypanel):** 
  - Since the target is Easypanel, the easiest CI/CD method is utilizing Easypanel's Webhook.
  - Provide a GitHub Actions step that sends a `POST` request (via `curl` or a webhook action) to the Easypanel Webhook URL to trigger a pull and rebuild on the VPS.
  - Assume the webhook URL is stored in GitHub Secrets as `${{ secrets.EASYPANEL_WEBHOOK_URL }}`.
- *Alternative Job 2 (If user prefers Docker Registry):* Build the Docker image and push it to GitHub Container Registry (GHCR) or DockerHub, then trigger Easypanel. (Prioritize the Webhook method first as it's the standard Easypanel flow).

---
*Note for AI: Please output only the code blocks for the `Dockerfile`, `.dockerignore`, and `.github/workflows/deploy.yml`, along with brief instructions on how to set the GitHub Secrets.*