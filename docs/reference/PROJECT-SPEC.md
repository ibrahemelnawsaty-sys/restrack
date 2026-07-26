> ⚠️ **مرجع تاريخي — ليس مصدر الحقيقة.**
> هذه المواصفات التفصيلية كُتبت في بداية المشروع، وبعض تفاصيلها تجاوزتها القرارات الحالية
> (مثال: الإطار **Laravel 12** لا 11 · الفيديو **مستضاف داخل المنصة بالكامل** لا Vimeo/YouTube).
> المصدر الرسمي لِما نبنيه: [`../plan/ROADMAP.md`](../plan/ROADMAP.md) و [`../plan/MASTER_PLAN.md`](../plan/MASTER_PLAN.md).
> قواعد *كيفية* العمل: [`/CLAUDE.md`](../../CLAUDE.md).
> نُبقي هذا الملف لِما فيه من تفصيلٍ مفيد (مخطط قاعدة البيانات، قوائم الميزات، هيكل المجلدات).

---

<div align="center">

# 🎓 Restrack Platform

### **From Beginner to Expert**

**منصة تدريبية طبية عالمية واحترافية**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-gold?style=for-the-badge)](LICENSE)

---

*Restrack is a world-class, bilingual (AR/EN) medical training platform designed to deliver structured learning paths with video lectures, randomized exams, automated certificates, and a fully customizable admin CMS — deployed on Hostinger Shared Hosting.*

</div>

---

## 📋 Table of Contents

- [Project Overview](#-project-overview)
- [Architecture & Tech Stack](#-architecture--tech-stack)
- [Complete Feature Set](#-complete-feature-set)
- [Speakers Management System](#-speakers-management-system)
- [MySQL Database Schema](#-mysql-database-schema)
- [Admin CMS — Full Page Control](#-admin-cms--full-page-control)
- [SEO Management System](#-seo-management-system)
- [Additional Professional Systems](#-additional-professional-systems)
- [API Reference](#-api-reference)
- [Installation & Setup](#-installation--setup)
- [Environment Variables](#-environment-variables)
- [Deployment](#-deployment)
- [Testing](#-testing)
- [Project Timeline](#-project-timeline)
- [Brand Identity](#-brand-identity)

---

## 🌍 Project Overview

| Item | Detail |
|------|--------|
| **Project Name** | Restrack — Research Track |
| **Type** | Medical Educational / Training Platform |
| **Languages** | Arabic (RTL) + English (LTR) — Full bilingual support |
| **Target Users** | Medical professionals, researchers, students |
| **Pricing Model** | One-time payment (899 SAR) for full access |
| **Learning Levels** | 3 structured levels (Beginner → Intermediate → Expert) |
| **Hosting** | Hostinger Shared Hosting |
| **Payment Gateway** | Moyasar / HyperPay (Mada, Apple Pay, Visa, MasterCard) |

### Core Value Proposition

Restrack provides a comprehensive medical research training program covering international guidelines (CONSORT, STROBE, PRISMA) and national standards (NCBE, SFDA). The platform supports self-paced learning with video lectures, randomized exams from a question bank, and automated PDF certificate generation.

---

## 🏗 Architecture & Tech Stack

```
┌─────────────────────────────────────────────────────────────┐
│                     RESTRACK PLATFORM                       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐  │
│  │  Guest Pages │  │  Student     │  │  Admin CMS       │  │
│  │  (Public)    │  │  Dashboard   │  │  Dashboard       │  │
│  │              │  │              │  │                  │  │
│  │  • Landing   │  │  • Progress  │  │  • Page Editor   │  │
│  │  • Speakers  │  │  • Lectures  │  │  • SEO Manager   │  │
│  │  • Pricing   │  │  • Exams     │  │  • Users CRUD    │  │
│  │  • Login     │  │  • Certs     │  │  • Content CRUD  │  │
│  │  • Register  │  │  • Surveys   │  │  • Analytics     │  │
│  │  • Checkout  │  │              │  │  • Speakers CRUD │  │
│  └──────────────┘  └──────────────┘  └──────────────────┘  │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                    BACKEND — Laravel 11                      │
│  Blade Templates │ Eloquent ORM │ Spatie Permission │ Mail  │
├─────────────────────────────────────────────────────────────┤
│                    DATABASE — MySQL 8.0                      │
│  28+ Tables │ Migrations │ Seeders │ Foreign Keys           │
├─────────────────────────────────────────────────────────────┤
│                    INFRASTRUCTURE                           │
│  Hostinger Shared │ SSL │ Cron Jobs │ SMTP │ CDN            │
└─────────────────────────────────────────────────────────────┘
```

### Technology Decisions

| Technology | Role | Justification |
|-----------|------|---------------|
| **Laravel 11** | Backend Framework | Full-featured MVC, Eloquent ORM, built-in Auth, runs on shared hosting |
| **Blade Templates** | Server-side Views | No build step required, excellent SEO, fast rendering |
| **Alpine.js 3** | Frontend Interactivity | 4KB, no npm needed, tabs/sliders/modals/dropdowns |
| **Tailwind CSS** | Design System | CDN for dev, pre-built CSS for production, RTL support |
| **MySQL 8.0** | Database | Available on Hostinger, robust, well-supported by Eloquent |
| **Spatie Permission** | Roles & Permissions | Industry-standard Laravel package for RBAC |
| **DomPDF** | Certificate Generation | Dynamic PDF with student name, score, certificate number |
| **Moyasar / HyperPay** | Payment Gateway | Saudi-based, supports Mada + Apple Pay + cards |
| **Laravel Mail** | Transactional Emails | SMTP with Hostinger, confirmation & notification emails |
| **Laravel Localization** | i18n (AR/EN) | Built-in, automatic RTL/LTR switching |

---

## 🎯 Complete Feature Set

### 1. Authentication & User Management
- Registration with email verification
- Login / Logout with session management
- Password reset (forgot password flow)
- Role-based access: `super_admin`, `admin`, `student`
- Profile management (name, email, avatar, language preference)
- Account deactivation / deletion
- Login activity logging (IP, device, timestamp)

### 2. Courses & Levels System
- 3 structured learning levels with ordered progression
- Content locking: level N+1 unlocks only after passing level N exam
- Each level contains ordered video lectures
- Bilingual content (AR/EN) for all titles, descriptions, and materials
- Downloadable resources (PDF handouts, slides) per lecture

### 3. Video Player & Progress Tracking
- Embedded video player (Vimeo / YouTube)
- Auto-save last playback position per lecture per user
- Lecture completion tracking (marks complete after reaching threshold)
- Level progress calculation (e.g., 4/6 lectures completed = 66%)
- Visual progress indicators on dashboard

### 4. Question Bank & Examination System
- Question bank per level with unlimited capacity
- Question types: Multiple Choice (MCQ), True/False
- Randomized exam generation from the question bank
- Configurable: number of questions per exam, passing score per level
- Unlimited exam attempts
- Automatic grading with instant results
- Exam history with detailed review

### 5. Certificate Generation
- 3 level certificates + 1 final certificate
- Dynamic PDF generation via DomPDF
- Certificate includes: student name, score, date, unique certificate number
- Downloadable and printable
- Certificate verification page (public URL with certificate number)

### 6. Subscriptions & Payment System
- Checkout page with program summary
- Saudi payment gateway integration (Moyasar / HyperPay)
- Supported methods: Mada, Apple Pay, Visa, MasterCard
- Webhook-based automatic subscription activation
- Payment confirmation email
- Payment history and invoicing
- Coupon / promo code system
- Refund management

### 7. Survey & Feedback System
- Post-completion quality survey
- Rating dimensions: content quality, clarity, speaker quality, tech quality, ease of use
- Recommendation tracking (would recommend: yes/no)
- Free-text suggestions field
- Admin analytics dashboard for survey results

### 8. Multilingual Support (AR/EN)
- Full RTL/LTR automatic switching
- Laravel Localization with language files
- User-preferred language stored in profile
- URL-based or session-based language switching
- All content, labels, errors, and emails in both languages

---

## 🎤 Speakers Management System

### Overview
A comprehensive, fully-featured speaker management system that positions Restrack as a professional training platform with world-class instructors.

### Speaker Profile Model

```php
// Speaker fields
$speaker = [
    'id',
    'name_ar', 'name_en',                    // Full name (bilingual)
    'title_ar', 'title_en',                  // Academic title
    'specialization_ar', 'specialization_en', // Specialization
    'bio_ar', 'bio_en',                       // Full biography
    'short_bio_ar', 'short_bio_en',           // Summary (for cards)
    'photo',                                  // Profile photo path
    'cover_photo',                            // Cover/banner photo
    'email',                                  // Contact email
    'phone',                                  // Contact phone
    'achievements',                           // JSON array of achievements
    'qualifications',                         // JSON array of qualifications
    'social_links',                           // JSON: twitter, linkedin, orcid, scholar
    'affiliated_institutions',                // JSON array of institutions
    'lectures_count',                         // Cached count
    'courses_taught',                         // JSON array of course IDs
    'years_of_experience',
    'display_order',                          // Sort order on frontend
    'is_featured',                            // Show in homepage slider
    'is_visible',                             // Published/draft
    'meta_title', 'meta_description',         // SEO for speaker page
    'slug',                                   // URL-friendly identifier
    'created_at', 'updated_at',
];
```

### Speaker Features

| Feature | Description |
|---------|-------------|
| **Speaker Profile Pages** | Dedicated `/speakers/{slug}` page for each speaker with full bio, achievements, courses |
| **Homepage Slider** | Alpine.js carousel showing featured speakers on the landing page |
| **Admin CRUD** | Full Create, Read, Update, Delete with drag-and-drop ordering |
| **Photo Management** | Photo upload with automatic resize/optimization, crop tool |
| **Social Links** | Twitter, LinkedIn, ORCID, Google Scholar integration |
| **Speaker-Lecture Linking** | Link speakers to their lectures/levels for attribution |
| **Achievement Badges** | Visual badges for certifications, publications, awards |
| **Availability Status** | Show active, on-leave, or guest speaker status |
| **Speaker Analytics** | Track page views, student engagement per speaker |

---

## 🗄 MySQL Database Schema

### Complete Table Structure (28 Tables)

#### Core Tables

```sql
-- 1. Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    locale ENUM('ar', 'en') DEFAULT 'ar',
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_locale (locale)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Levels
CREATE TABLE levels (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order` TINYINT UNSIGNED NOT NULL,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_ar TEXT NULL,
    description_en TEXT NULL,
    learning_outcomes_ar JSON NULL,
    learning_outcomes_en JSON NULL,
    topics_ar JSON NULL,
    topics_en JSON NULL,
    passing_score TINYINT UNSIGNED DEFAULT 70,
    exam_questions_count TINYINT UNSIGNED DEFAULT 30,
    is_published BOOLEAN DEFAULT FALSE,
    icon VARCHAR(50) NULL,
    color VARCHAR(7) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_order (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Lectures
CREATE TABLE lectures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    level_id BIGINT UNSIGNED NOT NULL,
    speaker_id BIGINT UNSIGNED NULL,
    `order` SMALLINT UNSIGNED NOT NULL,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_ar TEXT NULL,
    description_en TEXT NULL,
    video_url VARCHAR(500) NOT NULL,
    video_provider ENUM('vimeo', 'youtube', 'custom') DEFAULT 'vimeo',
    duration_minutes SMALLINT UNSIGNED NULL,
    thumbnail VARCHAR(255) NULL,
    resources JSON NULL,
    is_published BOOLEAN DEFAULT TRUE,
    is_free_preview BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE,
    FOREIGN KEY (speaker_id) REFERENCES speakers(id) ON DELETE SET NULL,
    INDEX idx_level_order (level_id, `order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Speakers
CREATE TABLE speakers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    title_ar VARCHAR(255) NULL,
    title_en VARCHAR(255) NULL,
    specialization_ar VARCHAR(255) NULL,
    specialization_en VARCHAR(255) NULL,
    bio_ar TEXT NULL,
    bio_en TEXT NULL,
    short_bio_ar VARCHAR(500) NULL,
    short_bio_en VARCHAR(500) NULL,
    photo VARCHAR(255) NULL,
    cover_photo VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    achievements JSON NULL,
    qualifications JSON NULL,
    social_links JSON NULL,
    affiliated_institutions JSON NULL,
    years_of_experience TINYINT UNSIGNED NULL,
    display_order SMALLINT UNSIGNED DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(500) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_order_visible (display_order, is_visible),
    INDEX idx_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Subscriptions
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'active', 'expired', 'cancelled', 'refunded') DEFAULT 'pending',
    payment_id VARCHAR(255) NULL,
    payment_method VARCHAR(50) NULL,
    payment_gateway ENUM('moyasar', 'hyperpay', 'manual') DEFAULT 'moyasar',
    amount DECIMAL(8,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'SAR',
    coupon_id BIGINT UNSIGNED NULL,
    discount_amount DECIMAL(8,2) DEFAULT 0,
    activated_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    refunded_at TIMESTAMP NULL,
    gateway_response JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL,
    INDEX idx_user_status (user_id, status),
    INDEX idx_payment (payment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Student Progress
CREATE TABLE student_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    lecture_id BIGINT UNSIGNED NOT NULL,
    last_position_seconds INT UNSIGNED DEFAULT 0,
    watch_duration_seconds INT UNSIGNED DEFAULT 0,
    is_completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lecture_id) REFERENCES lectures(id) ON DELETE CASCADE,
    UNIQUE INDEX idx_user_lecture (user_id, lecture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Questions (Bank)
CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    level_id BIGINT UNSIGNED NOT NULL,
    question_ar TEXT NOT NULL,
    question_en TEXT NOT NULL,
    type ENUM('mcq', 'true_false') DEFAULT 'mcq',
    options_ar JSON NOT NULL,
    options_en JSON NOT NULL,
    correct_answer VARCHAR(10) NOT NULL,
    explanation_ar TEXT NULL,
    explanation_en TEXT NULL,
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE,
    INDEX idx_level_active (level_id, is_active),
    INDEX idx_difficulty (difficulty)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Exam Attempts
CREATE TABLE exam_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    level_id BIGINT UNSIGNED NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    total_questions TINYINT UNSIGNED NOT NULL,
    correct_answers TINYINT UNSIGNED NOT NULL,
    passed BOOLEAN DEFAULT FALSE,
    time_spent_seconds INT UNSIGNED NULL,
    questions_snapshot JSON NOT NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE CASCADE,
    INDEX idx_user_level (user_id, level_id),
    INDEX idx_passed (passed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Certificates
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    level_id BIGINT UNSIGNED NULL,
    certificate_number VARCHAR(50) UNIQUE NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    type ENUM('level', 'final') DEFAULT 'level',
    file_path VARCHAR(255) NULL,
    issued_at TIMESTAMP NOT NULL,
    verified_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES levels(id) ON DELETE SET NULL,
    INDEX idx_certificate_number (certificate_number),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### CMS & Admin Tables

```sql
-- 10. Page Sections (CMS)
CREATE TABLE page_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(100) NOT NULL,
    section_key VARCHAR(100) NOT NULL,
    title_ar TEXT NULL,
    title_en TEXT NULL,
    subtitle_ar TEXT NULL,
    subtitle_en TEXT NULL,
    content_ar LONGTEXT NULL,
    content_en LONGTEXT NULL,
    image VARCHAR(255) NULL,
    background_image VARCHAR(255) NULL,
    cta_text_ar VARCHAR(255) NULL,
    cta_text_en VARCHAR(255) NULL,
    cta_url VARCHAR(500) NULL,
    extra_data JSON NULL,
    display_order SMALLINT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE INDEX idx_page_section (page_slug, section_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. SEO Meta
CREATE TABLE seo_meta (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(100) UNIQUE NOT NULL,
    meta_title_ar VARCHAR(255) NULL,
    meta_title_en VARCHAR(255) NULL,
    meta_description_ar VARCHAR(500) NULL,
    meta_description_en VARCHAR(500) NULL,
    meta_keywords_ar VARCHAR(500) NULL,
    meta_keywords_en VARCHAR(500) NULL,
    og_title_ar VARCHAR(255) NULL,
    og_title_en VARCHAR(255) NULL,
    og_description_ar VARCHAR(500) NULL,
    og_description_en VARCHAR(500) NULL,
    og_image VARCHAR(255) NULL,
    canonical_url VARCHAR(500) NULL,
    robots VARCHAR(100) DEFAULT 'index, follow',
    schema_markup JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_page (page_slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Site Settings
CREATE TABLE site_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL,
    `key` VARCHAR(100) NOT NULL,
    value_ar TEXT NULL,
    value_en TEXT NULL,
    type ENUM('text', 'textarea', 'image', 'boolean', 'number', 'json', 'color') DEFAULT 'text',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE INDEX idx_group_key (`group`, `key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Navigations (Header/Footer)
CREATE TABLE navigations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location ENUM('header', 'footer', 'sidebar') NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    label_ar VARCHAR(255) NOT NULL,
    label_en VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    target ENUM('_self', '_blank') DEFAULT '_self',
    icon VARCHAR(50) NULL,
    display_order SMALLINT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parent_id) REFERENCES navigations(id) ON DELETE CASCADE,
    INDEX idx_location_order (location, display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Media Library
CREATE TABLE media (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT UNSIGNED NOT NULL,
    path VARCHAR(500) NOT NULL,
    alt_text_ar VARCHAR(255) NULL,
    alt_text_en VARCHAR(255) NULL,
    folder VARCHAR(100) DEFAULT 'general',
    uploaded_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_folder (folder),
    INDEX idx_mime (mime_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Coupons
CREATE TABLE coupons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    value DECIMAL(8,2) NOT NULL,
    max_uses INT UNSIGNED NULL,
    used_count INT UNSIGNED DEFAULT 0,
    starts_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Survey Responses
CREATE TABLE survey_responses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    content_quality TINYINT UNSIGNED NOT NULL,
    clarity TINYINT UNSIGNED NOT NULL,
    speaker_quality TINYINT UNSIGNED NOT NULL,
    tech_quality TINYINT UNSIGNED NOT NULL,
    ease_of_use TINYINT UNSIGNED NOT NULL,
    overall_satisfaction TINYINT UNSIGNED NULL,
    would_recommend BOOLEAN DEFAULT TRUE,
    suggestions TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Activity Log
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    model_type VARCHAR(100) NULL,
    model_id BIGINT UNSIGNED NULL,
    description TEXT NULL,
    properties JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_action (user_id, action),
    INDEX idx_model (model_type, model_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Notifications
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data JSON NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_notifiable (notifiable_type, notifiable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. FAQs
CREATE TABLE faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_ar TEXT NOT NULL,
    question_en TEXT NOT NULL,
    answer_ar TEXT NOT NULL,
    answer_en TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    display_order SMALLINT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Contact Messages
CREATE TABLE contact_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    replied_by BIGINT UNSIGNED NULL,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. Email Templates
CREATE TABLE email_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    subject_ar VARCHAR(255) NOT NULL,
    subject_en VARCHAR(255) NOT NULL,
    body_ar LONGTEXT NOT NULL,
    body_en LONGTEXT NOT NULL,
    variables JSON NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. Guidelines (scrolling bar)
CREATE TABLE guidelines (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    url VARCHAR(500) NULL,
    type ENUM('international', 'national') DEFAULT 'international',
    display_order SMALLINT UNSIGNED DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spatie Permission tables (auto-generated)
-- 23. roles
-- 24. permissions
-- 25. model_has_roles
-- 26. model_has_permissions
-- 27. role_has_permissions
-- Laravel default tables
-- 28. password_reset_tokens
-- 29. sessions
-- 30. cache / cache_locks
-- 31. jobs / failed_jobs
```

---

## ⚙️ Admin CMS — Full Page Control

### The Admin can edit EVERYTHING on every public page:

| Capability | Details |
|-----------|---------|
| **Header / Navbar** | Edit logo, navigation links, CTA button text, colors |
| **Hero Section** | Edit title, subtitle, description, CTA button, background image |
| **About Section** | Edit goals, values, vision, mission text & icons |
| **Speakers Section** | Full CRUD: add/edit/delete/reorder speakers, manage photos |
| **Guidelines Bar** | Add/remove/reorder guideline logos (CONSORT, STROBE, etc.) |
| **Program Card** | Edit pricing, features list, CTA text |
| **Learning Framework** | Edit level tabs content, topics, learning outcomes |
| **Why Choose Us** | Edit features and target audience sections |
| **Footer** | Edit links, social media URLs, copyright text |
| **Login / Register Pages** | Edit welcome messages, background images, terms text |
| **Checkout Page** | Edit payment instructions, support info |
| **Site Settings** | Logo, favicon, site name, contact info, social links |
| **Navigation Manager** | Drag-and-drop menu editor for header & footer |
| **Email Templates** | Edit all transactional email templates (subject + body) |
| **Media Library** | Central media manager for all uploaded images/files |
| **FAQ Management** | CRUD for frequently asked questions |
| **Contact Messages** | View & reply to visitor messages |

### Admin CMS Routes

```
/admin/dashboard           → Analytics & Overview
/admin/pages/{slug}/edit   → Page Section Editor
/admin/settings            → Site Settings (logo, name, colors, contact)
/admin/navigation          → Header/Footer Menu Editor
/admin/speakers            → Speakers CRUD
/admin/guidelines          → Guidelines CRUD
/admin/levels              → Levels & Lectures Management
/admin/questions           → Question Bank CRUD
/admin/users               → User Management
/admin/subscriptions       → Subscription Management
/admin/payments            → Payment History
/admin/coupons             → Coupon Management
/admin/certificates        → Certificate Management
/admin/surveys             → Survey Results & Analytics
/admin/seo                 → SEO Manager (per page)
/admin/media               → Media Library
/admin/emails              → Email Template Editor
/admin/faqs                → FAQ Management
/admin/contacts            → Contact Messages
/admin/activity-log        → Activity Log Viewer
```

---

## 🔍 SEO Management System

### Admin-Controlled SEO Per Page

The admin has **full control** over SEO settings for every page:

| SEO Feature | Implementation |
|------------|---------------|
| **Meta Title** | Bilingual, editable per page |
| **Meta Description** | Bilingual, max 160 chars with counter |
| **Meta Keywords** | Tag input, bilingual |
| **Open Graph** | og:title, og:description, og:image per page |
| **Twitter Cards** | twitter:card, twitter:title, twitter:description |
| **Canonical URLs** | Configurable per page |
| **Robots Meta** | index/noindex, follow/nofollow per page |
| **JSON-LD Schema** | Structured data editor (Course, Organization, Person) |
| **Sitemap** | Auto-generated XML sitemap (`/sitemap.xml`) |
| **robots.txt** | Editable from admin panel |
| **Hreflang Tags** | Auto-generated for AR/EN language variants |
| **Breadcrumbs** | Schema-enabled breadcrumb navigation |

### Technical SEO (Auto-implemented)

- Lazy loading for images
- WebP image conversion
- Minified CSS/JS in production
- Gzip compression via .htaccess
- Browser caching headers
- Clean URL structure
- 301 redirect manager
- 404 custom page
- Page speed optimization

---

## 🌐 World-Class & Enterprise Features (New Integrations)

To elevate Restrack to a top-tier medical platform (competing with global EdTech standards), the following advanced systems are incorporated into the roadmap:

### 1. B2B / Institutional Sales System
- **Institution Accounts:** Allow hospitals or universities to buy bulk seats.
- **Bulk License Management:** Generate access codes for 50-100 doctors at a time.
- **B2B Dashboard:** Institutions can track their staff's progress, scores, and certificates.
- **SSO Integration:** SAML/OAuth integration for large hospital IT networks.

### 2. Advanced Proctoring & Anti-Cheat
- **Browser Lock:** Prevent switching tabs or opening new windows during exams.
- **Time Limits Per Question:** Stricter exam control to ensure knowledge retrieval.
- **Copy/Paste Prevention:** Disable text selection and clipboard actions during tests.

### 3. Interactive Community & Engagement
- **Discussion Forums:** Threaded Q&A below each lecture for peer-to-peer learning.
- **Direct Messaging / Ticketing:** Private channels to reach instructors or support.
- **Live Webinars:** Integration with Zoom / MS Teams API for blended learning.

### 4. Advanced Gamification
- **Points & Badges:** Earn digital badges for perfect scores or fast completions.
- **Learning Streaks:** Encourage daily logins with consecutive streak trackers.
- **Leaderboards:** Optional public or private ranking system for top learners.

### 5. Medical CME Credits Integration
- **Credit Allocation:** Assign specific Continuing Medical Education (CME) hours to courses.
- **SCFHS Integration Readiness:** Collect practitioner ID numbers during registration.
- **Automated Reporting:** Generate CME compliance reports for health authorities.

### 6. Advanced Video Delivery & Accessibility
- **SCORM / xAPI Support:** Future-proof architecture for standardized e-learning modules.
- **Chapters & Timecodes:** Allow jumping exactly to specific topics within a long video.
- **Transcripts & VTT Subtitles:** Closed captions for accessibility and non-native speakers.
- **In-Video Note Taking:** Students can add private notes linked to specific video timestamps.

### 7. Mobile App Readiness (PWA & API)
- **API Tokens:** Full Sanctum/Passport API structure built on Day 1 for future mobile apps.
- **PWA (Progressive Web App):** Installable web app allowing offline caching of resources.

### 8. Accessibility (a11y) & WCAG Compliance
- Screen reader compatibility, high contrast modes, and full keyboard navigation.

---

## 🚀 Additional Professional Core Systems

### 1. Notification Center
- In-app notifications (bell icon)
- Email notifications for: payment confirmation, new certificate, exam results
- Admin notifications: new registration, new payment, failed payment
- Configurable notification preferences per user

### 2. Analytics Dashboard (Admin)
- Total users (registered / active / subscribed)
- Revenue tracking (daily / weekly / monthly)
- Course completion rates per level
- Exam pass/fail ratios
- Dropout analytics: Track exactly where learners stop watching a video
- Item Difficulty Index (identify questions where >80% fail)

### 3. Security & Performance
- CSRF protection on all forms
- XSS & SQL Injection prevention (Blade & Eloquent)
- Rate limiting on login & API endpoints
- Cache optimization (Laravel Cache + View Caching)

### 4. Coupon & Promo System
- Fixed amount or percentage discounts with maximum uses and expirations.
- Track usage counts via Admin CRUD.

---

## 📁 Laravel Directory Structure

```
restrack/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Login, Register, Password Reset
│   │   │   ├── Admin/          # All admin controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PageSectionController.php
│   │   │   │   ├── SeoController.php
│   │   │   │   ├── SpeakerController.php
│   │   │   │   ├── LevelController.php
│   │   │   │   ├── LectureController.php
│   │   │   │   ├── QuestionController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── SubscriptionController.php
│   │   │   │   ├── CouponController.php
│   │   │   │   ├── CertificateController.php
│   │   │   │   ├── SurveyController.php
│   │   │   │   ├── NavigationController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   ├── MediaController.php
│   │   │   │   ├── EmailTemplateController.php
│   │   │   │   ├── FaqController.php
│   │   │   │   ├── ContactController.php
│   │   │   │   └── GuidelineController.php
│   │   │   ├── Student/        # Student dashboard controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── LectureController.php
│   │   │   │   ├── ExamController.php
│   │   │   │   ├── CertificateController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   └── SurveyController.php
│   │   │   ├── HomeController.php
│   │   │   ├── SpeakerController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── PaymentWebhookController.php
│   │   │   └── CertificateVerificationController.php
│   │   ├── Middleware/
│   │   │   ├── SetLocale.php
│   │   │   ├── CheckSubscription.php
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/           # Form validation
│   ├── Models/
│   │   ├── User.php, Level.php, Lecture.php, Speaker.php
│   │   ├── Subscription.php, StudentProgress.php
│   │   ├── Question.php, ExamAttempt.php, Certificate.php
│   │   ├── PageSection.php, SeoMeta.php, SiteSetting.php
│   │   ├── Navigation.php, Media.php, Coupon.php
│   │   ├── SurveyResponse.php, ContactMessage.php
│   │   ├── Faq.php, EmailTemplate.php, Guideline.php
│   │   └── ActivityLog.php
│   ├── Services/               # Business logic
│   │   ├── PaymentService.php
│   │   ├── CertificateService.php
│   │   ├── ExamService.php
│   │   ├── SeoService.php
│   │   └── MediaService.php
│   └── Notifications/
├── database/
│   ├── migrations/             # 30+ migration files
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/            # app.blade.php, admin.blade.php, student.blade.php
│   │   ├── pages/              # landing, about, speakers, checkout
│   │   ├── auth/               # login, register, passwords
│   │   ├── student/            # dashboard, lectures, exams, certificates
│   │   ├── admin/              # all admin views
│   │   ├── components/         # Blade components
│   │   └── emails/             # email templates
│   └── lang/
│       ├── ar/                 # Arabic translations
│       └── en/                 # English translations
├── routes/
│   ├── web.php                 # Public + Auth routes
│   ├── admin.php               # Admin routes (middleware: auth, admin)
│   └── student.php             # Student routes (middleware: auth, subscribed)
├── public/
│   ├── css/, js/, images/
│   └── .htaccess
├── .env
├── composer.json
└── README.md
```

---

## ⚙️ Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/your-org/restrack.git
cd restrack

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure .env (database, mail, payment gateway)

# 6. Run migrations
php artisan migrate

# 7. Seed initial data
php artisan db:seed

# 8. Create admin user
php artisan restrack:create-admin

# 9. Link storage
php artisan storage:link

# 10. Start development server
php artisan serve
```

---

## 🔑 Environment Variables

```env
# Application
APP_NAME=Restrack
APP_ENV=production
APP_URL=https://restrack.com
APP_LOCALE=ar

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restrack_db
DB_USERNAME=restrack_user
DB_PASSWORD=secure_password

# Mail (Hostinger SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@restrack.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl

# Payment Gateway
MOYASAR_API_KEY=
MOYASAR_SECRET_KEY=
MOYASAR_CALLBACK_URL=https://restrack.com/payment/callback

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## 🚀 Deployment (Hostinger Shared Hosting)

1. Upload project files via File Manager or SSH
2. Point `public_html` to the `public/` directory
3. Import database via phpMyAdmin
4. Configure `.env` with production credentials
5. Run `php artisan migrate --force`
6. Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`
7. Set up Cron Job: `* * * * * php /home/user/restrack/artisan schedule:run >> /dev/null 2>&1`
8. Enable SSL via Hostinger panel
9. Test all payment flows in sandbox mode before going live

---

## 📅 Project Timeline

| Phase | Duration | Deliverables |
|-------|----------|-------------|
| **Phase 1** | Week 1-2 | Laravel setup, Auth, Roles, Multilingual, Base Layout |
| **Phase 2** | Week 3-4 | Landing Page (8 sections), CMS for all sections |
| **Phase 3** | Week 5-6 | Payment gateway, Checkout, Subscription system, Coupons |
| **Phase 4** | Week 7-9 | Student area: Dashboard, Lectures, Exams, Certificates, Surveys |
| **Phase 5** | Week 10-11 | Admin CMS: All CRUD panels, Analytics, SEO Manager, Media Library |
| **Phase 6** | Week 12 | Testing, Optimization, SSL, RTL testing, Sitemap, Launch |

**Total Estimated Duration: 12 Weeks**

---

## 🎨 Brand Identity

| Element | Value |
|---------|-------|
| **Primary Color (Navy)** | `#16264b` — Backgrounds, Navbar, Dark sections |
| **Secondary Color (Gold)** | `#af9136` — CTAs, Highlighted text, Borders, Accents |
| **Gold Light** | `#d4b660` — Hover states, Secondary highlights |
| **Gold Dark** | `#8a7028` — Active states, Shadows |
| **Font** | Modern sans-serif (Arabic + English compatible) |
| **Direction** | RTL (Arabic default), LTR (English) |

---

<div align="center">

**Restrack** © 2026 — All Rights Reserved

*Built with ❤️ using Laravel 11*

</div>
